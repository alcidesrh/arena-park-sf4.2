<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contract
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("read_reservation")
     */
    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeFile()
    {
        $fs = new Filesystem();
        $path = pathinfo($this->getPath());
        $fs->remove($path['dirname']);
    }
}
