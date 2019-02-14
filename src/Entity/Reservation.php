<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={"order"={"createAt": "DESC"}, "pagination_client_items_per_page"=true}, normalizationContext={"groups"={"read_reservation"}})
 * @ApiFilter(SearchFilter::class, properties={"user": "exact"})
 * @ApiFilter(DateFilter::class, properties={"dateCarIn"})
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("read_reservation")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("read_reservation")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read_reservation")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contract", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups("read_reservation")
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read_reservation")
     */
    private $car;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("read_reservation")
     */
    private $dateCarIn;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("read_reservation")
     */
    private $dateCarOut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("read_reservation")
     */
    private $fly;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read_reservation")
     */
    private $airport;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups("read_reservation")
     */
    private $services;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("read_reservation")
     */
    private $descount;

    /**
     * @ORM\Column(type="integer")
     * @Groups("read_reservation")
     */
    private $paymentType;

    /**
     * @ORM\Column(type="float")
     * @Groups("read_reservation")
     */
    private $payment;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("read_reservation")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("read_reservation")
     */
    private $baggage;

    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getDateCarIn()
    {
        return $this->dateCarIn;
    }

    public function setDateCarIn(\DateTimeInterface $dateCarIn): self
    {
        $this->dateCarIn = $dateCarIn;

        return $this;
    }

    public function getDateCarOut()
    {
        return $this->dateCarOut;
    }

    public function setDateCarOut(\DateTimeInterface $dateCarOut): self
    {
        $this->dateCarOut = $dateCarOut;

        return $this;
    }

    public function getFly(): ?string
    {
        return $this->fly;
    }

    public function setFly(string $fly): self
    {
        $this->fly = $fly;

        return $this;
    }

    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    public function setAirport(?Airport $airport): self
    {
        $this->airport = $airport;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param mixed $services
     */
    public function setServices($services): void
    {
        $this->services = $services;
    }

    /**
     * @return mixed
     */
    public function getDescount()
    {
        return $this->descount;
    }

    /**
     * @param mixed $descount
     */
    public function setDescount($descount): void
    {
        $this->descount = $descount;
    }



    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function setPaymentType($paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getPayment(): ?float
    {
        return $this->payment;
    }

    public function setPayment(float $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getBaggage(): ?bool
    {
        return $this->baggage;
    }

    public function setBaggage(?bool $baggage): self
    {
        $this->baggage = $baggage;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'date' => $this->dateCarIn->format('Y-m-d'),
            'user' => $this->user,
            'payment' => $this->payment
        ];
    }


}
