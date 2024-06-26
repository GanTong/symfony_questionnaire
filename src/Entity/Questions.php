<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Identifier of question e.g. 2a
     *
     * @ORM\Column(type="string", length=10)
     */
    private $identifier;

    /**
     * Question content
     *
     * @ORM\Column(type="string", length=500)
     */
    private $label;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_multichoice;

    /**
     * Version.id
     *
     * @ORM\Column(type="smallint")
     */
    private $version_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getVersionId(): ?int
    {
        return $this->version_id;
    }

    public function setVersionId(int $version_id): self
    {
        $this->version_id = $version_id;

        return $this;
    }
}
