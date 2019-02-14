<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"normalization_context"={"groups"={"page_read"}}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("page_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("page_read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("page_read")
     */
    private $slug;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Section",
     *      mappedBy="page")
     * @Groups("page_read")
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function setSections(string $sections): self
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }


}
