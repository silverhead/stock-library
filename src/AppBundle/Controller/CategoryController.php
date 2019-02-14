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
     * @Route("/list/sub/{id}/{page}", name="category_sub_list", methods="GET", defaults={"page"=1})
     * @Route("/list/{page}", name="category_index", methods="GET", defaults={"page"=1})
     */
    public function index(Category $parent = null, int $page): Response
    {
        if($page < 1){
            $page = 1;
        }

        $nbItem = 10;
        $start = ($page-1) * $nbItem;

        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');

        if (null !== $parent){
            $categories = $categoryRepository->findByPaginate(array("parent" => $parent), array('lft' => 'ASC'), $start, $nbItem);
        }
        else{
            $categories = $categoryRepository->findByPaginate(array("lvl" => 0), array('lft' => 'ASC'), $start, $nbItem);
        }

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($categories) / $nbItem),
            'nomRoute' => $parent == null ?'product_index':'category_sub_list',
            'paramsRoute' => $parent == null?array(): array('id'=>$parent->getId())
        );

        return $this->render('category/index.html.twig', [
            'parent' => $parent,
            'categories' => $categories,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new/{parent}", name="category_new", methods="GET|POST", defaults={"parent"=null})
     */
    public function new(Request $request, $parent = null): Response
    {
        $category = new Category();
        if (null !== $parent){
            $categoryRepo = $this->getDoctrine()->getRepository("AppBundle:Category");
            $catParent =  $categoryRepo->find($parent);
            $category->setParent($catParent);
            $category->setRoot($catParent);
        }

        $form = $this->createForm(CategoryType::class, $category);

        if ($this->handleForm($form, $category, $request)){
            if (null !== $category->getParent()){
                return $this->redirectToRoute('category_sub_list', array(
                    'id' => $category->getParent()->getId()
                ));
            }
            else{
                return $this->redirectToRoute('category_index');
            }
        }

        return $this->render('category/new.html.twig', [
            'parent' => $parent,
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="category_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Category $category): Response
    {
        $doctrine = $this->getDoctrine();

        $documentRepo = $doctrine->getRepository('AppBundle:DocumentCategory');
        $documents = $documentRepo->findBy(array(
            'category' => $category
        ));

        $categoryRepo = $doctrine->getRepository('AppBundle:Category');
        $productRepo = $doctrine->getRepository('AppBundle:Product');
        $storageRepo = $doctrine->getRepository('AppBundle:Storage');

        $documentForm = $this->get('app.service.document_form');
        $documentForm->setForm(New DocumentCategory());
        if ($documentForm->handlerForm($request, $category)){
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        $products = $productRepo->findProductsByCategory($category, array('p.label' => 'ASC'));

        return $this->render('category/show.html.twig', [
            'categoryRepo' => $categoryRepo,
            'storageRepo' => $storageRepo,
            'category' => $category,
            'documentForm'=> $documentForm,
            'documents' => $documents,
            'products' => $products
        ]);
    }

    /**
     * @Route("/edit/{id}", name="category_edit", methods="GET|POST")
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        if ($this->handleForm($form, $category, $request)){
            if (null !== $category->getParent()){
                return $this->redirectToRoute('category_sub_list', array(
                    'id' => $category->getParent()->getId()
                ));
            }
            else{
                return $this->redirectToRoute('category_index');
            }
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
     * @Route("/delete/{id}", name="category_delete", methods="DELETE")
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
