<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidatesRepository")
 * @ApiResource
 */
class Candidates
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="candidates")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $stylesheet;

    /**
     * @ORM\Column(type="text")
     */
    private $infos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ElectionHasCandidates", mappedBy="candidate")
     */
    private $electionHasCandidates;

    public function __construct()
    {
        $this->electionHasCandidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStylesheet(): ?string
    {
        return $this->stylesheet;
    }

    public function setStylesheet(string $stylesheet): self
    {
        $this->stylesheet = $stylesheet;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    /**
     * @return Collection|ElectionHasCandidates[]
     */
    public function getElectionHasCandidates(): Collection
    {
        return $this->electionHasCandidates;
    }

    public function addElectionHasCandidate(ElectionHasCandidates $electionHasCandidate): self
    {
        if (!$this->electionHasCandidates->contains($electionHasCandidate)) {
            $this->electionHasCandidates[] = $electionHasCandidate;
            $electionHasCandidate->setCandidate($this);
        }

        return $this;
    }

    public function removeElectionHasCandidate(ElectionHasCandidates $electionHasCandidate): self
    {
        if ($this->electionHasCandidates->contains($electionHasCandidate)) {
            $this->electionHasCandidates->removeElement($electionHasCandidate);
            // set the owning side to null (unless already changed)
            if ($electionHasCandidate->getCandidate() === $this) {
                $electionHasCandidate->setCandidate(null);
            }
        }

        return $this;
    }
}
