<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Table as Table;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Table(name="user")
 * @ApiResource
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"candidates_read"})
     */
    protected $id;

    /**
     * @var string
     * @Groups({"candidates_read"})
     */
    protected $username;
    /**
     * @var string
     *  @Groups({"candidates_read"})
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidate", mappedBy="userRelated")
     */
    protected $election;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Election", mappedBy="voter")
     */
    protected $candidates;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;


    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->elections = new ArrayCollection();
    }

    public function getElection(): ?Election
    {
        return $this->election;
    }

    public function setElection(?Election $election): self
    {
        $this->election = $election;

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->setUserRelated($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->contains($candidate)) {
            $this->candidates->removeElement($candidate);
            // set the owning side to null (unless already changed)
            if ($candidate->getUserRelated() === $this) {
                $candidate->setUserRelated(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * @return Collection|Election[]
     */
    public function getElections(): Collection
    {
        return $this->elections;
    }

    public function addElection(Election $election): self
    {
        if (!$this->elections->contains($election)) {
            $this->elections[] = $election;
            $election->addVoter($this);
        }

        return $this;
    }

    public function removeElection(Election $election): self
    {
        if ($this->elections->contains($election)) {
            $this->elections->removeElement($election);
            $election->removeVoter($this);
        }

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }
}
