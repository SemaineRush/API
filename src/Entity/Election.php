<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ElectionRepository")
 * @ORM\Table(name="election")
 * @ApiResource(
 * normalizationContext={
 *     "groups"={"election_read"}
 *  },
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
     * @Groups({"candidates_read","election_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"candidates_read","election_read"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"candidates_read","election_read"})
     */
    private $endduration;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"candidates_read","election_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"candidates_read","election_read"})
     */
    private $localisation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Candidate", inversedBy="elections")
     * @Groups({"election_read"})
     */
    private $candidateElection;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="elections")
     * @Groups({"election_read"})
     */
    private $voter;



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

    public function getEndduration(): ?\DateTimeInterface
    {
        return $this->endduration;
    }

    public function setEndduration(\DateTimeInterface $endduration): self
    {
        $this->endduration = $endduration;

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
        }

        return $this;
    }

    public function removeVoter(User $voter): self
    {
        if ($this->voter->contains($voter)) {
            $this->voter->removeElement($voter);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
