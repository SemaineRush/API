<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElectionHasCandidatesRepository")
 * @ApiResource
 */
class ElectionHasCandidates
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Candidates", inversedBy="electionHasCandidates")
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Elections", inversedBy="electionHasElections")
     */
    private $election;

    /**
     * @ORM\Column(type="integer")
     */
    private $vote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?Candidates
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidates $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getElection(): ?Elections
    {
        return $this->election;
    }

    public function setElection(?Elections $election): self
    {
        $this->election = $election;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
