<?php

namespace App\Service;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use App\Validation\QuestionnaireValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ProductsRepository
     */
    private $productsRepository;

    /**
     * @var QuestionnaireValidation
     */
    private $questionnaireValidation;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ProductsRepository $productsRepository
     * @param QuestionnaireValidation $questionnaireValidation
     */
    public function __construct(EntityManagerInterface $entityManager,
        ProductsRepository $productsRepository,
        QuestionnaireValidation $questionnaireValidation
    )
    {
        $this->entityManager = $entityManager;
        $this->productsRepository = $productsRepository;
        $this->questionnaireValidation = $questionnaireValidation;
    }

    /**
     * get behaviour configuration
     *
     * @return array
     */
    public function findProductBehaviourConfiguration(): array
    {
        // get all products entries
        $result = $this->productsRepository->findAll();

        $array = [];
        foreach ($result as $key => $res) {
            // encode configuration rules into a JSON object
            $array[$key]['rules'] = json_encode($res->getBehaviourConfiguration());
        }

        return $array;
    }

    /**
     * sanitise behaviour configuration
     * find the corresponding product id from question identifier and choice answer combination
     * return behaviour_description and restriction_description
     *
     * @param $questionIdentifier
     * @param $choiceNumber
     * @return array
     */
    public function findProductIdByQuestionIdentifierAndChoiceNumber($questionIdentifier, $choiceNumber): array
    {
        $productList = $this->findProductBehaviourConfiguration();
        $config = [];

        $i=1;
        foreach ($productList as &$val)
        {
            // remove the square brackets
            $val['rules'] = trim($val['rules'], '[]');

            // split the string by commas
            $val['rules'] = explode(',', $val['rules']);

            // remove quotes and trim each element
            $config['rules'][$i] = array_map(function($element) {
                return trim($element, '"');
            }, $val['rules']);
            $i++;
        }

        $productId = '';

        // find $questionIdentifier-$choiceNumber value from the configuration list and get the product id
        foreach ($config['rules'] as $key => $value){
            if (in_array("{$questionIdentifier}-{$choiceNumber}", $value)) {
                $productId = $key;
            }
        }

        // find product entry by id
        $res = $this->productsRepository->find($productId);

        // return necessary data
        return [
           'behaviour_description' => $res->getBehaviourDescription(),
           'restriction_description' => $res->getRestrictionDescription()
        ];
    }

    public function createProduct(string $productIdentifier, string $behaviourDescription, string $behaviourConfig, string $restrictionDescription): array
    {
        // validate request
        $this->questionnaireValidation->validateProducts($productIdentifier, $behaviourDescription, $behaviourConfig, $restrictionDescription);

        // store product
        $product = new Products();
        $product->setProductIdentifier($productIdentifier);
        $product->setBehaviourDescription($behaviourDescription);
        $product->setBehaviourConfiguration((array)$behaviourConfig);
        $product->setRestrictionDescription($restrictionDescription);
        $this->productsRepository->add($product, true);

        return [
            'id' => $product->getId(),
            'product_identifier' => $product->getProductIdentifier(),
            'behaviour_description' => $product->getBehaviourDescription(),
            'behaviour_configuration' => $product->getBehaviourConfiguration(),
            'restriction_description' => $product->getRestrictionDescription()
        ];
    }

    public function updateProduct($id, array $data): Products
    {
        if(!$product = $this->productsRepository->find($id)) {
            throw new NotFoundHttpException('Product is not found.');
        }

        if (isset($data['product_identifier'])) {
            $product->setProductIdentifier($data['product_identifier']);
        }

        if (isset($data['behaviour_description'])) {
            $product->setBehaviourDescription($data['behaviour_description']);
        }

        if (isset($data['behaviour_configuration'])) {
            $product->setBehaviourConfiguration($data['behaviour_configuration']);
        }

        if (isset($data['restriction_description'])) {
            $product->setRestrictionDescription($data['restriction_description']);
        }

        $this->productsRepository->add($product, true);

        return $product;
    }

}
