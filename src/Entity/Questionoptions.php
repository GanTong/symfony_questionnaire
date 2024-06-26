<?php

namespace App\Entity;

use App\Repository\QuestionoptionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionoptionsRepository::class)
 */
class Questionoptions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Questions.identifier
     *
     * @ORM\Column(type="string")
     */
    private $question_identifier;

    /**
     * Accept multiple label value for multi-choice question types
     *
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $label;

    /**
     * Option choice number corresponding to label
     * Accepts only alphabet
     *
     * @ORM\Column(type="integer", length=5)
     */
    private $option_choice_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionIdentifier(): ?string
    {
        return $this->question_identifier;
    }

    public function setQuestionIdentifier(string $question_identifier): self
    {
        $this->question_identifier = $question_identifier;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getOptionChoiceNumber(): ?int
    {
        return $this->option_choice_number;
    }

    public function setOptionChoiceNumber(int $option_choice_number): self
    {
        $this->option_choice_number = $option_choice_number;

        return $this;
    }

}
