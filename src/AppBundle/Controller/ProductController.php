<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductByUser;
use AppBundle\Form\DocumentType;
use AppBundle\Form\Model\ProductModel;
use AppBundle\Form\ProductModelType;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\SearchProductModel;
use AppBundle\Form\SearchProductType;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/products", name="product_index", methods="GET")
     */
    public function index(): Response
    {
        $doctrine = $this->get('doctrine');

        $productRepository = $doctrine->getRepository('AppBundle:Product');
        $categoryRepo = $doctrine->getRepository('AppBundle:Category');
        $storageRepo = $doctrine->getRepository('AppBundle:Storage');

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categoryRepo' => $categoryRepo,
            'storageRepo' => $storageRepo
        ] );
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $productModel = new ProductModel($product, $this->getUser());
        $form = $this->createForm(ProductModelType::class, $productModel, array(
            'user' => $this->getUser(),
            'picturePath' => $product->getPictureWebPath()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveProduct($form, $product);

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $productModel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Product $product): Response
    {

        $doctrine = $this->getDoctrine();
        $productUserRepo = $doctrine->getRepository("AppBundle:ProductByUser");
        $categoryRepo = $doctrine->getRepository('AppBundle:Category');
        $storageRepo = $doctrine->getRepository('AppBundle:Storage');

        $documentForm = $this->createForm(DocumentType::class, new Document());


        return $this->render('product/show.html.twig', [
            'product' => $product,
            'productInfoForUser' => $productUserRepo->findOnceByProductAndUser($product, $this->getUser()),
            'productQuantityAnotherUser' => $productUserRepo->findAllByProductAndAnotherUser($product, $this->getUser()),
            'categoryRepo' => $categoryRepo,
            'storageRepo' => $storageRepo,
            'documentForm' => $documentForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product): Response
    {
        $productModel = new ProductModel($product, $this->getUser());
        $form = $this->createForm(ProductModelType::class, $productModel, array(
            'user' => $this->getUser(),
            'picturePath' => $product->getPictureWebPath()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveProduct($form, $product);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }


    public function searchProductAction()
    {
        $searchProductModel = new SearchProductModel();
        $form = $this->createForm(SearchProductType::class, $searchProductModel);

        // replace this example code with whatever you need
        return $this->render('product/search.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/search_result", name="product_search_result")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchResultAction(Request $request)
    {
        $searchProductModel = new SearchProductModel();
        $form = $this->createForm(SearchProductType::class, $searchProductModel);
        $form->handleRequest($request);

        $productList = array();

        $doctrine = $this->get('doctrine');
        $categoryRepo = $doctrine->getRepository('AppBundle:Category');
        $storageRepo = $doctrine->getRepository('AppBundle:Storage');

        if ($form->isSubmitted() && $form->isValid()) {

            $searchProductModel = $form->getData();

            $productRepository = $doctrine->getRepository('AppBundle:Product');

            $productList = $productRepository->searchByLabel($searchProductModel->getSearch());
        }


        // replace this example code with whatever you need
        return $this->render('product/searchResult.html.twig', [
            'search' => $searchProductModel->getSearch(),
            'productList' => $productList,
            'categoryRepo' => $categoryRepo,
            'storageRepo' => $storageRepo,
        ]);
    }

    private function saveProduct($form, Product $product)
    {
        $uplodableManager = $this->container->get('stof_doctrine_extensions.uploadable.manager');

        $productModel = $form->getData();
        $product->setByProductModel($productModel);

        $productByUser = new ProductByUser();
        $productByUserList = $product->getProductByUserFiltered($this->getUser());

        if (null !== $productByUserList && $productByUserList->count() == 1){
            $productByUser = $productByUserList[0];
        }

        $productByUser->setUser($this->getUser());
        $productByUser->setProduct($product);
        $productByUser->setByProductModel($productModel);

        if ($product->getPictureFile() instanceof UploadedFile) {
            $uplodableManager->markEntityToUpload($product, $product->getPictureFile());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->persist($productByUser);
        $em->flush();
    }
}
