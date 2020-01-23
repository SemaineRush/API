<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Schema\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateRepository")
 * @ORM\Table(name="candidate")
 * @ApiResource(
 * normalizationContext={
 *     "groups"={"candidates_read"}
 *  },
 *      collectionOperations={"get"},
 *      itemOperations={"GET","PUT","DELETE","increment"={
 *              "method"="post", 
 *              "path"="/invoices/{id}/increment",
 *              "controller"="App\Controller\InvoiceIncrementationController",
 *              "swagger_context"={"summary"="string", "description"="date-time"},
 *          }
 *     },
 * )
 * 
 */
class Candidate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"candidates_read","election_read"})
     */
    private $id;



    /**
     * @ORM\Column(type="text")
     * @Groups({"candidates_read","election_read"})
     */
    private $stylesheet;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Election", mappedBy="candidateElection")
     * @Groups({"candidates_read"})
     */
    private $elections;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="candidate")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"candidates_read"})
     */
    private $userRelated;

    /**
     * @ORM\Column(type="json")
     * @Groups({"candidates_read","election_read"})
     */
    private $informations = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="candidate")
     */
    private $scores;


    public function __construct()
    {
        $this->elections = new ArrayCollection();
        $this->scores = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $election->addCandidateElection($this);
        }

        return $this;
    }

    public function removeElection(Election $election): self
    {
        if ($this->elections->contains($election)) {
            $this->elections->removeElement($election);
            $election->removeCandidateElection($this);
        }

        return $this;
    }


    public function __toString()
    {
        return $this->userRelated->getEmail();
    }

    public function getUserRelated(): ?User
    {
        return $this->userRelated;
    }

    public function setUserRelated(?User $userRelated): self
    {
        $this->userRelated = $userRelated;

        return $this;
    }

    public function getInformations(): ?array
    {
        return $this->informations;
    }

    public function setInformations(array $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setCandidate($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getCandidate() === $this) {
                $score->setCandidate(null);
            }
        }

        return $this;
    }
}