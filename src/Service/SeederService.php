<?php

namespace App\Service;

use App\Entity\Products;
use App\Entity\Questionoptions;
use App\Entity\Questions;
use App\Entity\Version;
use Doctrine\ORM\EntityManagerInterface;

class SeederService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * abstract seeder for different entities
     * to be used for DataFixtures
     *
     * @param $entityName
     * @param $entity
     * @return void
     */
    public function seedEntity($entityName, $entity): void
    {
        switch ($entityName) {
            case 'products':
                foreach ($entity as $product) {

                    $record = new Products();
                    $record->setProductIdentifier($product['product_identifier']);
                    $record->setBehaviourDescription($product['behaviour_description']);
                    $record->setBehaviourConfiguration($product['behaviour_configuration_array']);
                    $record->setRestrictionDescription($product['restriction_description']);

                    $this->entityManager->persist($record);
                }
                break;
            case 'versions':
                foreach ($entity as $version) {
                    $record = new Version();
                    $record->setName($version['name']);

                    $this->entityManager->persist($record);
                }
                break;
            case 'questions':
                foreach ($entity as $question) {
                    $record = new Questions();
                    $record->setIdentifier($question['question_identifier']);
                    $record->setLabel($question['question_label']);
                    $record->setVersionId($question['version_id']);
                    $record->setIsMultichoice($question['is_multichoice']);

                    $this->entityManager->persist($record);
                }
                break;
            case 'questionOptions':
                foreach ($entity as $questionOption) {
                    $record = new Questionoptions();
                    $record->setLabel($questionOption['question_option_label']);
                    $record->setQuestionIdentifier($questionOption['question_identifier']);
                    $record->setOptionChoiceNumber($questionOption['question_choice_number']);

                    $this->entityManager->persist($record);
                }
                break;
        }
        $this->entityManager->flush();
    }

}
