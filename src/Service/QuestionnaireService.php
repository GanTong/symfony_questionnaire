<?php

namespace App\Service;

use App\Entity\Answers;
use App\Entity\Questionoptions;
use App\Entity\Questions;
use App\Entity\Version;
use App\Repository\AnswersRepository;
use App\Repository\ProductsRepository;
use App\Repository\QuestionoptionsRepository;
use App\Repository\QuestionsRepository;
use App\Repository\VersionRepository;
use App\Validation\QuestionnaireValidation;
use Doctrine\ORM\EntityManagerInterface;

class QuestionnaireService
{

    /**
     * @var VersionRepository
     */
    private $versionRepository;
    /**
     *
     * @var QuestionsRepository
     */
    private $questionsRepository;

    /**
     * @var QuestionoptionsRepository
     */
    private $questionoptionsRepository;

    /**
     * @var QuestionnaireValidation
     */
    private $questionnaireValidation;

    /**
     * @var AnswersRepository
     */
    private $answersRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductsRepository
     */
    private $productsRepository;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @param VersionRepository $versionRepository
     * @param QuestionsRepository $questionsRepository
     * @param QuestionoptionsRepository $questionoptionsRepository
     * @param QuestionnaireValidation $questionnaireValidation
     * @param AnswersRepository $answersRepository
     * @param ProductsRepository $productsRepository
     * @param ProductService $productService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(VersionRepository $versionRepository,
        QuestionsRepository $questionsRepository,
        QuestionoptionsRepository $questionoptionsRepository,
        QuestionnaireValidation $questionnaireValidation,
        AnswersRepository $answersRepository,
        ProductsRepository $productsRepository,
        ProductService $productService,
        EntityManagerInterface $entityManager
    )
    {
        $this->versionRepository = $versionRepository;
        $this->questionsRepository = $questionsRepository;
        $this->questionoptionsRepository = $questionoptionsRepository;
        $this->questionnaireValidation = $questionnaireValidation;
        $this->answersRepository = $answersRepository;
        $this->productsRepository = $productsRepository;
        $this->productService = $productService;
        $this->entityManager = $entityManager;
    }

    /**
     * create version
     *
     * @param string $versionName
     * @return array
     */
    public function createVersion(string $versionName): array
    {
        // validate request
        $this->questionnaireValidation->validateVersions($versionName);

        // store version
        $version = new Version();
        $version->setName($versionName);
        $this->versionRepository->add($version, true);

        return [
            'id' => $version->getId(),
            'name' => $version->getName()
        ];
    }

    /**
     * create question
     *
     * @param string $identifier
     * @param int $versionId
     * @param string $label
     * @param $isMultiChoice
     * @return array
     */
    public function createQuestion(string $identifier, int $versionId, string $label, $isMultiChoice): array
    {

        // validate request
        $this->questionnaireValidation->validateQuestions($identifier, $versionId, $label, $isMultiChoice);
        $multiChoice = $this->questionnaireValidation->sanitiseInput($isMultiChoice);

        // store question
        $question = new Questions();
        $question->setIdentifier($identifier);
        $question->setLabel($label);
        $question->setIsMultichoice($multiChoice);
        $question->setVersionId($versionId);
        $this->questionsRepository->add($question, true);

        return [
            'id' => $question->getId(),
            'identifier' => $question->getIdentifier(),
            'label' => $question->getLabel(),
            'is_multichoice' => $question->isMultichoice(),
            'version_id' => $question->getVersionId()
        ];
    }

    /**
     * create question options
     *
     * @param string $questionIdentifier
     * @param $questionOptionsLabel
     * @param $optionChoiceNumber
     * @return array
     */
    public function createQuestionOptions(string $questionIdentifier, $questionOptionsLabel, $optionChoiceNumber): array
    {

        // validate request
        $this->questionnaireValidation->validateQuestionOptions($questionIdentifier, $questionOptionsLabel);
        $this->questionnaireValidation->validatorRulesForChoice($optionChoiceNumber);

        // Label can be one or multiple entries
        if (is_array($questionOptionsLabel)) {
            $labels = $questionOptionsLabel;
        } else {
            $labels = (array)$questionOptionsLabel;
        }

        // loop through label array and store question options
        foreach ($labels as $label) {
            $questionOptions = new Questionoptions();
            $questionOptions->setQuestionIdentifier($questionIdentifier);
            $questionOptions->setLabel($label);
            $questionOptions->setOptionChoiceNumber($optionChoiceNumber);
            $this->questionoptionsRepository->add($questionOptions, true);
        }

        // find entry by label and question identifier
        $questionOptionsRes = $this->questionoptionsRepository->findQuestionOptionsByLabelsAndQuestionIdentifier($labels, $questionIdentifier);

        $result = [];
        foreach ($questionOptionsRes as $index => $val) {
            $result[$index]['id'] = $val->getId();
            $result[$index]['question_identifier'] = $val->getQuestionIdentifier();
            $result[$index]['label'] = $val->getLabel();
            $result[$index]['option_choice_number'] = $val->getOptionChoiceNumber();
        }

        return $result;
    }

    /**
     * answer questionnaire
     *
     * @param string|null $customerName
     * @param string $questionIdentifier
     * @param $isMultichoice
     * @param $answerChoice
     * @param string|null $answerText
     * @return array
     */
    public function answerQuestion(?string $customerName, string $questionIdentifier, $isMultichoice, $answerChoice, ?string $answerText = null): array
    {

        // validate request
        $this->questionnaireValidation->validateAnswers($customerName, $questionIdentifier, $isMultichoice);
        $answerIsMultiChoice = $this->questionnaireValidation->sanitiseInput($isMultichoice);

        if ($answerIsMultiChoice) {
            $this->questionnaireValidation->validatorRulesForChoice($answerChoice);
        }

        // store answer
        $answers = new Answers();
        $answers->setCustomerName($customerName);
        $answers->setQuestionIdentifer($questionIdentifier);
        $answers->setIsMultichoice($answerIsMultiChoice);
        $answerChoice = $answerIsMultiChoice ? $answerChoice : 0;
        $answers->setAnswerChoice($answerChoice);
        $answerText = $answerIsMultiChoice ? null : $answerText;
        $answers->setAnswerText($answerText);
        $this->answersRepository->add($answers, true);

        // find product entry by question identifier and answer choice for recommendations
        $recommendation = $this->productService->findProductIdByQuestionIdentifierAndChoiceNumber($questionIdentifier, $answerChoice);

        return [
            'id' => $answers->getId(),
            'customer_name' => $answers->getCustomerName(),
            'is_multichoice' => $answers->isMultichoice(),
            'question_identifier' => $answers->getQuestionIdentifier(),
            'answer_choice' => $answers->getAnswerChoice(),
            'answer_text' => $answers->getAnswerText(),
            'behaviour_description' => $recommendation['behaviour_description'],
            'restriction_description' => $recommendation['restriction_description']
        ];
    }

    /**
     * Get full questionnaire by version id
     *
     * @param $versionId
     * @return array
     */
    public function getQuestionnaireByVersionId($versionId): array
    {
        return $this->versionRepository->findQuestionnaireByVersionId($versionId);
    }

}