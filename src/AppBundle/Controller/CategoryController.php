<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\DocumentCategory;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="category_index", methods="GET")
     */
    public function index(): Response
    {
        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');

        return $this->render('category/index.html.twig', ['categories' => $categoryRepository->findBy(array(), array('lft' => 'ASC'))]);
    }

    /**
     * @Route("/new", name="category_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
            $categoryRepository->reorderHierarchy($category);

            $this->addFlash('success',"Rangement enregistré avec succès !");

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET", "POST"})
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
            $categoryRepository->reorderHierarchy($category);

            $this->addFlash('success',"Catégorie enregistrée avec succès !");

            return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
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
}
