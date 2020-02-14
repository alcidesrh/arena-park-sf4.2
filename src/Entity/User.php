<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={"order"={"name": "ASC"}, "pagination_client_items_per_page"=true}, normalizationContext={"groups"={"read_user"}})
 * @ApiFilter(SearchFilter::class, properties={"email": "partial", "name": "partial", "phone": "partial", "id": "exact"})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User //implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read_reservation", "read_user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_reservation", "read_user"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_reservation", "read_user"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_reservation", "read_user"})
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Car", mappedBy="user")
     * @Groups("read_user")
     */
    private $car;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="user", cascade={"persist", "remove"})
     * @Groups("read_user")
     */
    private $reservations;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read_reservation", "read_user"})
     */
    private $sex;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read_reservation", "read_user"})
     */
    private $unsubscribe;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_reservation", "read_user"})
     */
    private $dateDiscount;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar($car): void
    {
        $this->car = $car;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getLastReservation()
    {
        return $this->reservations->last();
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function setSex(bool $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getUnsubscribe()
    {
        return $this->unsubscribe;
    }

    public function setUnsubscribe(bool $unsubscribe): self
    {
        $this->unsubscribe = $unsubscribe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDiscount()
    {
        return $this->dateDiscount;
    }

    /**
     * @param mixed $dateFlyIn
     */
    public function setDateDiscount($dateDiscount): void
    {
        $this->dateDiscount = $dateDiscount;
    }

//     public function jsonSerialize()
//     {
//         return [
//             'id' => $this->id,
//             'name' => $this->name,
//             'sex' => $this->sex,
//             'email' => $this->email,
//             'phone' => $this->phone,
//             'car' => $this->getCar(),
//             'reservations' => $this->getReservations()
//         ];
//     }

}
