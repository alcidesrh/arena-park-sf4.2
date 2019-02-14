<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_urgence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMovil(): ?string
    {
        return $this->movil;
    }

    public function setMovil(?string $movil): self
    {
        $this->movil = $movil;

        return $this;
    }

    public function getPhoneUrgence(): ?string
    {
        return $this->phone_urgence;
    }

    public function setPhoneUrgence(?string $phone_urgence): self
    {
        $this->phone_urgence = $phone_urgence;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistre(): ?string
    {
        return $this->registre;
    }

    public function setRegistre(?string $registre): self
    {
        $this->registre = $registre;

        return $this;
    }
}
