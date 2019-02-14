<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TarifRepository")
 */
class Tarif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $day;

    /**
     * @ORM\Column(type="smallint")
     */
    private $priceCharge;

    /**
     * @ORM\Column(type="smallint")
     */
    private $annulation;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $descount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeDescount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getPriceCharge(): ?int
    {
        return $this->priceCharge;
    }

    public function setPriceCharge(int $priceCharge): self
    {
        $this->priceCharge = $priceCharge;

        return $this;
    }

    public function getAnnulation(): ?int
    {
        return $this->annulation;
    }

    public function setAnnulation(int $annulation): self
    {
        $this->annulation = $annulation;

        return $this;
    }

    public function getDescount()
    {
        return $this->descount;
    }

    public function setDescount(?int $descount): self
    {
        $this->descount = $descount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActiveDescount()
    {
        return $this->activeDescount;
    }

    /**
     * @param mixed $activeDescount
     */
    public function setActiveDescount($activeDescount): void
    {
        $this->activeDescount = $activeDescount;
    }


}
