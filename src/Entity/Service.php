<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(attributes={"order"={"priority": "ASC"}})
 * @ApiFilter(BooleanFilter::class, properties={"active"})
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     */
    private $prices = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $priority;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority ?? 0;
    }

    public function setPriority($priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }

}
