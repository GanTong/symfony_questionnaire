<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Service\QuestionnaireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionnaireContronller extends AbstractController
{
    /**
     * @var QuestionnaireService
     */
    private $questionnaireService;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @param QuestionnaireService $questionnaireService
     * @param ProductService $productService
     */
    public function __construct(QuestionnaireService $questionnaireService, ProductService $productService)
    {
        $this->questionnaireService = $questionnaireService;
        $this->productService = $productService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createVersion(Request $request): Response
    {
        try {
            $name = $request->query->get('name');
            $res = $this->questionnaireService->createVersion($name);

            return $this->json([
               'version_id' => $res['id'],
               'name' => $res['name'],
               'message' => 'success'
           ]);
        } catch (\Exception $exception) {
            return $this->json([
               'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createQuestion(Request $request): Response
    {
        try {
            $identifier = $request->query->get('identifier');
            $versionId = $request->query->get('version_id');
            $label = $request->query->get('label');
            $isMultiChoice = $request->query->get('is_multichoice');

            $res = $this->questionnaireService->createQuestion($identifier, $versionId, $label, $isMultiChoice);

            return $this->json([
               'question_id' => $res['id'],
               'question_identifier' => $res['identifier'],
               'label' => $res['label'],
               'is_multichoice' => $res['is_multichoice'],
               'version_id' => $res['version_id'],
               'message' => 'success'
           ]);
        } catch (\Exception $exception) {
            return $this->json([
               'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createQuestionOptions(Request $request): Response
    {
        try {
            $questionId = $request->query->get('question_id');
            $labels = $request->query->get('label');
            $optionChoiceNumber = $request->query->get('option_choice_number');

            $result = $this->questionnaireService->createQuestionOptions($questionId, $labels, $optionChoiceNumber);

            return $this->json([
               'result' => $result,
               'message' => 'success'
            ]);
        } catch (\Exception $exception) {
            return $this->json([
               'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function answerQuestion(Request $request): Response
    {
        try {
            $customerName = $request->query->get('customer_name') ?? null;
            $questionIdentifier = $request->query->get('question_identifier');
            $isMultichoice = $request->query->get('is_multichoice');
            $answerChoice = $request->query->get('answer_choice');
            $answerText = $request->query->get('answer_text') ?? null;

            $res = $this->questionnaireService->answerQuestion($customerName, $questionIdentifier, $isMultichoice, $answerChoice, $answerText);

            return $this->json([
               'answer_id' => $res['id'],
               'customer_name' => $res['customer_name'],
               'is_multichoice' => $res['is_multichoice'] ? 'multi-choice' : 'free text',
               'question_identifier' => $res['question_identifier'],
               'answer_choice' => $answerChoice,
               'text' => $res['answer_text'],
               'behaviour_description' => $res['behaviour_description'],
               'restriction_description' => $res['restriction_description'],
               'message' => 'success'
            ]);

        } catch (\Exception $exception) {
            return $this->json([
               'message' => $exception->getMessage()
           ]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getFullQuestionnaireByVersion(Request $request): Response
    {
        $versionId = $request->query->get('version_id');
        $res = $this->questionnaireService->getQuestionnaireByVersionId($versionId);

        return $this->json([
            'result' => $res
        ]);
    }

}