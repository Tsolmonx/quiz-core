<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Table(name: 'app_quiz')]
class Quiz
{
    public const STATE_PENDING = 'pending';
    public const STATE_APPROVED = 'approved';
    public const STATE_CANCELLED = 'cancelled';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $requestStatus = self::STATE_PENDING;

    #[ORM\Column]
    private ?int $level = 0;

    #[ORM\Column]
    private ?bool $isGrouped = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id', name: 'created_by')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true, referencedColumnName: 'id', name: 'parent_id')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private ?Collection $childrens = null;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuestionGroup::class, orphanRemoval: true)]
    private Collection $questionGroups;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: QuizImage::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuizTaker::class)]
    private Collection $quizTakers;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuizResponse::class)]
    private Collection $quizResponses;

    public function __construct()
    {
        $this->questionGroups = new ArrayCollection();
        $this->childrens = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
        $this->questions = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->quizTakers = new ArrayCollection();
        $this->quizResponses = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
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

    /**
     * @return Collection|null<int, Quiz>
     */
    public function getChildrens(): ?Collection
    {
        return $this->childrens;
    }

    public function addChildren(Quiz $quiz): void
    {
        if (!$this->childrens->contains($quiz)) {
            $this->childrens->add($quiz);
            $quiz->setParent($this);
        }
    }

    public function removeChildren(Quiz $quiz): void
    {
        if ($this->childrens->contains($quiz)) {
            $this->childrens->removeElement($quiz);
            $quiz->setParent(null);
        }
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

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuizImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(QuizImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setOwner($this);
        }

        return $this;
    }

    public function removeImage(QuizImage $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getOwner() === $this) {
                $image->setOwner(null);
            }
        }

        return $this;
    }

    public function getImagesByType(string $type)
    {
        return $this->images->filter(function (QuizImage $image) use ($type) {
            return $image->getType() === $type;
        });
    }

    /**
     * @return Collection<int, QuizTaker>
     */
    public function getQuizTakers(): Collection
    {
        return $this->quizTakers;
    }

    public function addQuizTaker(QuizTaker $quizTaker): static
    {
        if (!$this->quizTakers->contains($quizTaker)) {
            $this->quizTakers->add($quizTaker);
            $quizTaker->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizTaker(QuizTaker $quizTaker): static
    {
        if ($this->quizTakers->removeElement($quizTaker)) {
            // set the owning side to null (unless already changed)
            if ($quizTaker->getQuiz() === $this) {
                $quizTaker->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuizResponse>
     */
    public function getQuizResponses(): Collection
    {
        return $this->quizResponses;
    }

    public function addQuizResponse(QuizResponse $quizResponse): static
    {
        if (!$this->quizResponses->contains($quizResponse)) {
            $this->quizResponses->add($quizResponse);
            $quizResponse->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizResponse(QuizResponse $quizResponse): static
    {
        if ($this->quizResponses->removeElement($quizResponse)) {
            // set the owning side to null (unless already changed)
            if ($quizResponse->getQuiz() === $this) {
                $quizResponse->setQuiz(null);
            }
        }

        return $this;
    }

    public function getQuizResponsesByUserAndAttempt(User $user, int $attempt): Collection
    {
        return $this->quizResponses->filter(function (QuizResponse $quizResponse) use ($user, $attempt) {
            return $quizResponse->getQuizTaker() === $user && $quizResponse->getAttempt() === $attempt;
        });
    }
}
