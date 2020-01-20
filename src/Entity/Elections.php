<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElectionsRepository")
 * @ApiResource()
 */
class Elections
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", mappedBy="vote")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ElectionHasCandidates", mappedBy="election")
     */
    private $electionHasElections;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->electionHasElections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addVote($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeVote($this);
        }

        return $this;
    }

    /**
     * @return Collection|ElectionHasCandidates[]
     */
    public function getElectionHasElections(): Collection
    {
        return $this->electionHasElections;
    }

    public function addElectionHasElection(ElectionHasCandidates $electionHasElection): self
    {
        if (!$this->electionHasElections->contains($electionHasElection)) {
            $this->electionHasElections[] = $electionHasElection;
            $electionHasElection->setElection($this);
        }

        return $this;
    }

    public function removeElectionHasElection(ElectionHasCandidates $electionHasElection): self
    {
        if ($this->electionHasElections->contains($electionHasElection)) {
            $this->electionHasElections->removeElement($electionHasElection);
            // set the owning side to null (unless already changed)
            if ($electionHasElection->getElection() === $this) {
                $electionHasElection->setElection(null);
            }
        }

        return $this;
    }
}
