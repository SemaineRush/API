<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElectionRepository")
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 */
class Election
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
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="election")
     */
    private $voter;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Candidate", inversedBy="elections")
     */
    private $candidateElection;



    public function __construct()
    {
        $this->voter = new ArrayCollection();
        $this->candidateElection = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getVoter(): Collection
    {
        return $this->voter;
    }

    public function addVoter(User $voter): self
    {
        if (!$this->voter->contains($voter)) {
            $this->voter[] = $voter;
            $voter->setElection($this);
        }

        return $this;
    }

    public function removeVoter(User $voter): self
    {
        if ($this->voter->contains($voter)) {
            $this->voter->removeElement($voter);
            // set the owning side to null (unless already changed)
            if ($voter->getElection() === $this) {
                $voter->setElection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidateElection(): Collection
    {
        return $this->candidateElection;
    }

    public function addCandidateElection(Candidate $candidateElection): self
    {
        if (!$this->candidateElection->contains($candidateElection)) {
            $this->candidateElection[] = $candidateElection;
        }

        return $this;
    }

    public function removeCandidateElection(Candidate $candidateElection): self
    {
        if ($this->candidateElection->contains($candidateElection)) {
            $this->candidateElection->removeElement($candidateElection);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
