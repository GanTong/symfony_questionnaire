<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_identifier;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $behaviour_description;

    /**
     * behaviour_configuration in the form of questionIdentifier-choiceNumber
     *
     * @ORM\Column(type="json")
     */
    private $behaviour_configuration = [];

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $restriction_description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductIdentifier(): ?string
    {
        return $this->product_identifier;
    }

    public function setProductIdentifier(string $product_identifier): self
    {
        $this->product_identifier = $product_identifier;

        return $this;
    }

    public function getBehaviourDescription(): ?string
    {
        return $this->behaviour_description;
    }

    public function setBehaviourDescription(?string $behaviour_description): self
    {
        $this->behaviour_description = $behaviour_description;

        return $this;
    }

    public function getBehaviourConfiguration(): ?array
    {
        return $this->behaviour_configuration;
    }

    public function setBehaviourConfiguration(array $behaviour_configuration): self
    {
        $this->behaviour_configuration = $behaviour_configuration;

        return $this;
    }

    public function getRestrictionDescription(): ?string
    {
        return $this->restriction_description;
    }

    public function setRestrictionDescription(string $restriction_description): self
    {
        $this->restriction_description = $restriction_description;

        return $this;
    }

}
