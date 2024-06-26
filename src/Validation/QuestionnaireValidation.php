<?php

namespace App\Validation;

use App\Repository\QuestionoptionsRepository;
use App\Repository\QuestionsRepository;
use App\Repository\VersionRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionnaireValidation
{
    /**
     * @var VersionRepository
     */
    private $versionRepository;

    /**
     * @var QuestionsRepository
     */
    private $questionsRepository;
    /**
     * @var QuestionoptionsRepository
     */
    private $questionoptionsRepository;

    public function __construct(VersionRepository $versionRepository,
        QuestionsRepository $questionsRepository,
        QuestionoptionsRepository $questionoptionsRepository
    )
    {
        $this->versionRepository = $versionRepository;
        $this->questionsRepository = $questionsRepository;
        $this->questionoptionsRepository = $questionoptionsRepository;
    }

    /**
     * validation for creating versions
     *
     * @param $versionName
     * @return void
     */
    public function validateVersions($versionName): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($versionName, [
            new Length(['min' => 2]),
            new NotBlank(),
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

    /**
     * validation for creating questions
     *
     * @param $identifier
     * @param $versionId
     * @param $label
     * @param $isMultiChoice
     * @return void
     */
    public function validateQuestions($identifier, $versionId, $label, $isMultiChoice): void
    {
        if (!$this->versionRepository->find($versionId)) {
            throw new NotFoundHttpException('Version is not found.');
        }

        $validator = Validation::createValidator();

        $violations1 = $validator->validate($identifier, [
            new Length(['min' => 1, 'max' => 10]),
            new NotBlank(),
        ]);

        $violations2 = $validator->validate($label, [
            new Length(['min' => 2, 'max' => 500]),
            new NotBlank(),
        ]);

        $violations3 = $validator->validate($isMultiChoice, [
            new Choice(['yes', 'no', 'true', 'false', true, false, 1, 0, '1', '0']),
            new NotBlank(),
        ]);

        // Combine all violations
        $allViolations = array_merge(iterator_to_array($violations1), iterator_to_array($violations2), iterator_to_array($violations3));

        // Check if there are any violations
        if (0 !== count($allViolations)) {
            // There are errors, now you can show them
            foreach ($allViolations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

    /**
     * to sanitise mutichoice values and return as bool value
     *
     * @param $isMultiChoice
     * @return bool
     */
    public function sanitiseInput($isMultiChoice): bool
    {
        if ($isMultiChoice === 'yes') {
            return true;
        } elseif ($isMultiChoice === 'no') {
            return false;
        } else {
            return boolval($isMultiChoice);
        }
    }

    /**
     * validation for creating question options
     *
     * @param $questionIdentifier
     * @param $labelsRaw
     * @return void
     */
    public function validateQuestionOptions($questionIdentifier, $labelsRaw): void
    {
        if (!$this->questionsRepository->findByIdentifier($questionIdentifier)) {
            throw new NotFoundHttpException('Question identifier is not valid.');
        }

        $validator = Validation::createValidator();

        if (is_array($labelsRaw)) {
            foreach ($labelsRaw as $label) {
                $this->validatorRulesForQuestionOptions($validator, $label);
            }
        } else {
            $this->validatorRulesForQuestionOptions($validator, $labelsRaw);
        }
    }

    /**
     * additional validation for creating question options
     *
     * @param ValidatorInterface $validator
     * @param $label
     * @return void
     */
    public function validatorRulesForQuestionOptions(ValidatorInterface $validator, $label): void
    {
        $violations = $validator->validate($label, [
            new Length(['min' => 2, 'max' => 500]),
            new NotBlank(),
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

    /**
     * validations for creating answers
     *
     * @param $customerName
     * @param $questionIdentifier
     * @param $isMultichoice
     * @return void
     */
    public function validateAnswers($customerName, $questionIdentifier, $isMultichoice): void
    {
        if (!$this->questionoptionsRepository->findByChoiceNumber($questionIdentifier)) {
            throw new NotFoundHttpException('Question identifier is not valid.');
        }

        $validator = Validation::createValidator();

        $violations1 = $validator->validate($customerName, [
            new Length(['max' => 255])
        ]);

        $violations2 = $validator->validate($isMultichoice, [
            new Choice(['yes', 'no', 'true', 'false', true, false, 1, 0, '1', '0']),
            new NotBlank(),
        ]);

        // Combine all violations
        $allViolations = array_merge(iterator_to_array($violations1), iterator_to_array($violations2));

        // Check if there are any violations
        if (0 !== count($allViolations)) {
            // There are errors, now you can show them
            foreach ($allViolations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

    /**
     * validation for choice number field
     *
     * @param $answerChoice
     * @return void
     */
    public function validatorRulesForChoice($answerChoice): void
    {
        if (!$this->questionoptionsRepository->findByChoiceNumber($answerChoice)) {
            throw new NotFoundHttpException('Choice number is not valid.');
        }

        $validator = Validation::createValidator();

        $violations = $validator->validate($answerChoice, [
            new Length(['min' => 1]),
            new NotBlank(),
            new Regex(
                '/^\d+$/',
            'only numbers are allowed for answer choice')
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

    /**
     * validation for creating a product
     *
     * @param string $productIdentifier
     * @param string $behaviourDescription
     * @param string $behaviourConfiguration
     * @param string $restrictionDescription
     * @return void
     */
    public function validateProducts(string $productIdentifier, string $behaviourDescription, string $behaviourConfiguration, string $restrictionDescription): void
    {
        $validator = Validation::createValidator();

        $violations1 = $validator->validate($productIdentifier, [
            new Length(['max' => 255]),
            new NotBlank()
        ]);

        $violations2 = $validator->validate($behaviourDescription, [
            new Length(['max' => 500])
        ]);

        $violations3 = $validator->validate($behaviourConfiguration, [
            new Regex(
                '/^\d+[a-zA-Z]?\-\d+$/',
                'Each element must match the pattern digit-letter(optional)-digit'
            ),
            new NotBlank()
        ]);

        $violations4 = $validator->validate($restrictionDescription, [
            new Length(['max' => 500])
        ]);

        // Combine all violations
        $allViolations = array_merge(
            iterator_to_array($violations1),
            iterator_to_array($violations2),
            iterator_to_array($violations3),
            iterator_to_array($violations4)
        );

        // Check if there are any violations
        if (0 !== count($allViolations)) {
            // There are errors, now you can show them
            foreach ($allViolations as $violation) {
                throw new InvalidArgumentException($violation->getMessage());
            }
        }
    }

}
