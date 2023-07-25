<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get()
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['issue:read', 'issue:write'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'assignee', targetEntity: Issue::class)]
    private Collection $assignedIssues;

    #[ORM\OneToMany(mappedBy: 'reporter', targetEntity: Issue::class, orphanRemoval: true)]
    private Collection $reportedIssues;

    public function __construct()
    {
        $this->assignedIssues = new ArrayCollection();
        $this->reportedIssues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getAssignedIssues(): Collection
    {
        return $this->assignedIssues;
    }

    public function addIssue(Issue $issue): static
    {
        if (!$this->assignedIssues->contains($issue)) {
            $this->assignedIssues->add($issue);
            $issue->setAssignee($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): static
    {
        if ($this->assignedIssues->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getAssignee() === $this) {
                $issue->setAssignee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getReportedIssues(): Collection
    {
        return $this->reportedIssues;
    }

    public function addReportedIssue(Issue $reportedIssue): static
    {
        if (!$this->reportedIssues->contains($reportedIssue)) {
            $this->reportedIssues->add($reportedIssue);
            $reportedIssue->setReporter($this);
        }

        return $this;
    }

    public function removeReportedIssue(Issue $reportedIssue): static
    {
        if ($this->reportedIssues->removeElement($reportedIssue)) {
            // set the owning side to null (unless already changed)
            if ($reportedIssue->getReporter() === $this) {
                $reportedIssue->setReporter(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}