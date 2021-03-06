<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table as Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Table(name="character")
 * @UniqueEntity("email")
 * @ApiResource(
 *     normalizationContext={"groups"={"user_read"}},
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="email",type="string",unique=true)
     * @Groups({"user_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="jsonb")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Election", inversedBy="users")
     * @Groups({"user_read"})
     */
    private $election;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidate", mappedBy="userRelated", orphanRemoval=true)
     * @Groups({"user_read"})
     */
    private $candidate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_enable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_read"})
     */
    private $name;

    public function __construct()
    {
        $this->election = new ArrayCollection();
        $this->candidate = new ArrayCollection();
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

    public function __toString()
    {
        return $this->email;
    }

    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the hashed password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the hashed password
     *
     * @param  string  $password  The hashed password
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Election[]
     */
    public function getElection(): Collection
    {
        return $this->election;
    }

    public function addElection(Election $election): self
    {
        if (!$this->election->contains($election)) {
            $this->election[] = $election;
        }

        return $this;
    }

    public function removeElection(Election $election): self
    {
        if ($this->election->contains($election)) {
            $this->election->removeElement($election);
        }

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidate(): Collection
    {
        return $this->candidate;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate[] = $candidate;
            $candidate->setUserRelated($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidate->contains($candidate)) {
            $this->candidate->removeElement($candidate);
            // set the owning side to null (unless already changed)
            if ($candidate->getUserRelated() === $this) {
                $candidate->setUserRelated(null);
            }
        }

        return $this;
    }

    public function getIsEnable(): ?bool
    {
        return $this->is_enable;
    }

    public function setIsEnable(?bool $is_enable): self
    {
        $this->is_enable = $is_enable;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token)
    {
        $this->token = $token;
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
}
