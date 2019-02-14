<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("page_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("page_read")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("page_read")
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups("page_read")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="sections")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $page;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
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
