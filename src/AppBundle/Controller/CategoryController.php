<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\DocumentCategory;
use AppBundle\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/sub/{id}", name="category_sub_list", methods="GET")
     * @Route("/", name="category_index", methods="GET")
     */
    public function index(Category $parent = null): Response
    {
        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');

        if (null !== $parent){
            $categories = $categoryRepository->findBy(array("parent" => $parent), array('lft' => 'ASC'));
        }
        else{
            $categories = $categoryRepository->findBy(array("lvl" => 0), array('lft' => 'ASC'));
        }

        return $this->render('category/index.html.twig', [ 'parent' => $parent, 'categories' => $categories]);
    }

    /**
     * @Route("/new", name="category_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($this->handleForm($form, $category, $request)){
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="category_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Category $category): Response
    {
        $documentRepo = $this->getDoctrine()->getRepository('AppBundle:DocumentCategory');
        $documents = $documentRepo->findBy(array(
            'category' => $category
        ));

        $documentForm = $this->get('app.service.document_form');
        $documentForm->setForm(New DocumentCategory());
        if ($documentForm->handlerForm($request, $category)){
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render('category/show.html.twig', ['category' => $category, 'documentForm'=> $documentForm, 'documents' => $documents]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods="GET|POST")
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        if ($this->handleForm($form, $category, $request)){
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    private function handleForm(FormInterface $form, Category $category, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->beginTransaction();

            try{
                $em->persist($category);
                $em->flush();

                $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
                $categoryRepository->reorderHierarchy($category);

                $em->commit();

                $this->addFlash('success',"Catégorie enregistrée avec succès !");

                return true;
            }
            catch (\Exception $ex){

                $em->rollback();

                $this->addFlash('error',$ex->getMessage());
            }
        }

        return false;
    }

    /**
     * @Route("/{id}", name="category_delete", methods="DELETE")
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }


    public function menu()
    {
        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');

        return $this->render('category/menu.html.twig', ['categories' => $categoryRepository->findBy(array('lvl' => 0), array('lft' => 'ASC'))]);
    }
}
