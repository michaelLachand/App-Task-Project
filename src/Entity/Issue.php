<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Enum\IssueStatusEnum;
use App\Enum\IssueTypeEnum;
use App\Repository\IssueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            normalizationContext: ['groups' => ['issue:read']],
            denormalizationContext: ['groups' => ['issue:write']]
        ),
    ]
)]
class Issue
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups(['issue:read'])]
    private ?string $id = null;

    private ?int $key = null;

    #[ORM\ManyToOne(inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['issue:write'])]
    private ?Project $project = null;

    #[ORM\Column]
    #[Groups(['issue:read', 'issue:write'])]
    private IssueTypeEnum $type = IssueTypeEnum::BUG;

    #[ORM\Column]
    #[Groups(['issue:read', 'issue:write'])]
    private IssueStatusEnum $status = IssueStatusEnum::NEW;

    #[ORM\Column(length: 255)]
    #[Groups(['issue:read', 'issue:write'])]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['issue:read', 'issue:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['issue:read', 'issue:write'])]
    private ?int $storyPointEstimated = null;

    #[ORM\ManyToOne(inversedBy: 'issues')]
    #[Groups(['issue:read', 'issue:write'])]
    private ?User $assignee = null;

    #[ORM\ManyToOne(inversedBy: 'reportedIssues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['issue:read', 'issue:write'])]
    private ?User $reporter = null;

    #[ORM\PrePersist]
    public function setIdValue(): void
    {
        $this->id = $this->project->getKey().'-'.$this->project->getIssues()->count() + 1;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getType(): ?IssueTypeEnum
    {
        return $this->type;
    }

    public function setType(IssueTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?IssueStatusEnum
    {
        return $this->status;
    }

    public function setStatus(IssueStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStoryPointEstimated(): ?int
    {
        return $this->storyPointEstimated;
    }

    public function setStoryPointEstimated(?int $storyPointEstimated): static
    {
        $this->storyPointEstimated = $storyPointEstimated;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): static
    {
        $this->reporter = $reporter;

        return $this;
    }
}