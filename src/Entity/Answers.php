<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_name;

    /**
     * Questions.identifier
     *
     * @ORM\Column(type="string", length=10)
     */
    private $question_identifier;

    /**
     * 1 - multi-choice, 0 - free text
     *
     * @ORM\Column(type="boolean")
     */
    private $is_multichoice;

    /**
     * Questionoptions.id
     * Accepts only alphabet
     * default to 0 for free text
     * e.g. 1 means choice 1 for multi-choice
     *
     * @ORM\Column(type="integer", length=5)
     */
    private $answer_choice;

    /**
     * null if $is_multichoice
     *
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $answer_text;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(?string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getQuestionIdentifier(): ?string
    {
        return $this->question_identifier;
    }

    public function setQuestionIdentifer(string $question_identifier): self
    {
        $this->question_identifier = $question_identifier;

        return $this;
    }

    public function isMultichoice(): ?bool
    {
        return $this->is_multichoice;
    }

    public function setIsMultichoice(bool $is_multichoice): self
    {
        $this->is_multichoice = $is_multichoice;

        return $this;
    }

    public function getAnswerChoice(): ?int
    {
        return $this->answer_choice;
    }

    public function setAnswerChoice(int $answer_choice): self
    {
        $this->answer_choice = $answer_choice;

        return $this;
    }

    public function getAnswerText(): ?string
    {
        return $this->answer_text;
    }

    public function setAnswerText(?string $answer_text): self
    {
        $this->answer_text = $answer_text;

        return $this;
    }
}
