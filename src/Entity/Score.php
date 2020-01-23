<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidate", mappedBy="score")
     */
    private $candidate;

    public function __construct()
    {
        $this->candidate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $candidate->setScore($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidate->contains($candidate)) {
            $this->candidate->removeElement($candidate);
            // set the owning side to null (unless already changed)
            if ($candidate->getScore() === $this) {
                $candidate->setScore(null);
            }
        }

        return $this;
    }
}
