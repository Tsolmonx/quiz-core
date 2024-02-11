<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Table(name: 'app_quiz')]
#[ApiResource]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $requestStatus = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?bool $isGrouped = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id', name: 'created_by')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true, referencedColumnName: 'id', name: 'parent_id')]
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private ?Collection $childrens = null;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuestionGroup::class, orphanRemoval: true)]
    private Collection $questionGroups;

    public function __construct()
    {
        $this->questionGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRequestStatus(): ?string
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(string $requestStatus): static
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function isIsGrouped(): ?bool
    {
        return $this->isGrouped;
    }

    public function setIsGrouped(bool $isGrouped): static
    {
        $this->isGrouped = $isGrouped;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    /**
     * @return Collection<int, QuestionGroup>
     */
    public function getQuestionGroups(): Collection
    {
        return $this->questionGroups;
    }

    public function addQuestionGroup(QuestionGroup $questionGroup): static
    {
        if (!$this->questionGroups->contains($questionGroup)) {
            $this->questionGroups->add($questionGroup);
            $questionGroup->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestionGroup(QuestionGroup $questionGroup): static
    {
        if ($this->questionGroups->removeElement($questionGroup)) {
            // set the owning side to null (unless already changed)
            if ($questionGroup->getQuiz() === $this) {
                $questionGroup->setQuiz(null);
            }
        }

        return $this;
    }
}
