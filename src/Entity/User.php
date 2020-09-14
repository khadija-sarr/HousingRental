<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Veuillez indiquer votre adresse email.")
     * @Assert\Email(message="Veuillez indiquer une adresse email valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotNull(message="Veuillez indiquer votre statut.")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Veuillez indiquer votre prénom.")
     * @Assert\Length(max="30", maxMessage="Votre prénom ne doit pas dépasser les {{ limit }} caractères.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Veuillez indiquer votre nom.")
     * @Assert\Length(max="30", maxMessage="Votre nom ne doit pas dépasser les {{ limit }} caractères.")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Country(message="Veuillez indiquer la ville où vous vivez.")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Veuillez indiquer votre code postal.")
     * @Assert\Length(max="20", maxMessage="Veuillez saisir un code postal correct.")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez indiquer votre adresse postale.")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Veuillez indiquer votre numéro de téléphone.")
     * @Assert\Regex("/^([0-9]+)$/", message="Veuillez indiquer un numéro de téléphone correct.")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=House::class, mappedBy="user")
     */
    private $houses;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Veuillez indiquer la ville où vous vivez.")
     * @Assert\Length(max="30", maxMessage="Veuillez saisir un nom de ville correct.")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull(message="Veuillez indiquer votre genre.")
     */
    private $gender;

    public function __construct()
    {
        $this->houses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
     * @return Collection|House[]
     */
    public function getHouses(): Collection
    {
        return $this->houses;
    }

    public function addHouse(House $house): self
    {
        if (!$this->houses->contains($house)) {
            $this->houses[] = $house;
            $house->setUser($this);
        }

        return $this;
    }

    public function removeHouse(House $house): self
    {
        if ($this->houses->contains($house)) {
            $this->houses->removeElement($house);
            // set the owning side to null (unless already changed)
            if ($house->getUser() === $this) {
                $house->setUser(null);
            }
        }

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
