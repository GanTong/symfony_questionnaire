<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class ProductController extends AbstractController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var ProductsRepository
     */
    private $productsRepository;

    /**
     * @param ProductService $productService
     * @param ProductsRepository $productsRepository
     */
    public function __construct(ProductService $productService, ProductsRepository $productsRepository)
    {
        $this->productService = $productService;
        $this->productsRepository = $productsRepository;
    }

    /**
     * @Route("/createProduct", name="createProduct")
     *
     * @param Request $request
     * @return Response
     */
    public function createProduct(Request $request): Response
    {
        try {
            $productIdentifier = $request->query->get('product_identifier');
            $behaviourDescription = $request->query->get('behaviour_description');
            $behaviourConfiguration = $request->query->get('behaviour_configuration');
            $restrictionDescription = $request->query->get('restriction_description');

            $res = $this->productService->createProduct($productIdentifier, $behaviourDescription,
                                                        $behaviourConfiguration, $restrictionDescription);

            return $this->json([
               'product_id' => $res['id'],
               'product_identifier' => $res['product_identifier'],
               'behaviour_description' => $res['behaviour_description'],
               'question_identifier' => $res['behaviour_configuration'],
               'text' => $res['restriction_description'],
               'message' => 'success'
           ]);

        } catch (\Exception $exception) {
            return $this->json([
               'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @Route("/updateProduct/{id}", name="updateProduct")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProduct(Request $request, int $id) : Response
    {
        $productIdentifier = $request->query->get('product_identifier');
        $behaviourDescription = $request->query->get('behaviour_description');
        $behaviourConfiguration = $request->query->get('behaviour_configuration');
        $restrictionDescription = $request->query->get('restriction_description');

        $array = [
           'product_identifier' => $productIdentifier,
           'behaviour_description' => $behaviourDescription,
            'behaviour_configuration' => (array)$behaviourConfiguration,
            'restriction_description' => $restrictionDescription
        ];

        $res = $this->productService->updateProduct($id, $array);

        return $this->json([
            'product_id' => $res->getId(),
            'product_identifier' => $res->getProductIdentifier(),
            'behaviour_description' => $res->getBehaviourDescription(),
            'behaviour_configuration' => $res->getBehaviourConfiguration(),
            'restriction_description' => $res->getRestrictionDescription(),
            'message' => 'success'
        ]);
    }

}