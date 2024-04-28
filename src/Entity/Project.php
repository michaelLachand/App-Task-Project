<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    private ?string $keyCode = null;

    #[ORM\ManyToOne(inversedBy: 'leadedProjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leadUser = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    private Collection $numbers;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $issues;

    public function __construct()
    {
        $this->numbers = new ArrayCollection();
        $this->issues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getKeyCode(): ?string
    {
        return $this->keyCode;
    }

    public function setKeyCode(string $keyCode): static
    {
        $this->keyCode = $keyCode;

        return $this;
    }

    public function getLeadUser(): ?User
    {
        return $this->leadUser;
    }

    public function setLeadUser(?User $leadUser): static
    {
        $this->leadUser = $leadUser;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getNumbers(): Collection
    {
        return $this->numbers;
    }

    public function addNumber(User $number): static
    {
        if (!$this->numbers->contains($number)) {
            $this->numbers->add($number);
        }

        return $this;
    }

    public function removeNumber(User $number): static
    {
        $this->numbers->removeElement($number);

        return $this;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function addIssue(Issue $issue): static
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
            $issue->setProject($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): static
    {
        if ($this->issues->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getProject() === $this) {
                $issue->setProject(null);
            }
        }

        return $this;
    }
}
