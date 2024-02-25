<?php

namespace App\Entity;

use App\Repository\QuizResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizResponseRepository::class)]
#[ORM\Table(name: 'app_quiz_response')]
class QuizResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\ManyToOne(inversedBy: 'quizResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $quizTaker = null;

    #[ORM\Column]
    private ?int $attempt = null;

    #[ORM\OneToMany(mappedBy: 'quizResponse', targetEntity: QuestionResponse::class, orphanRemoval: true)]
    private Collection $questionResponses;

    #[ORM\Column(nullable: true)]
    private ?int $totalRightAnswers = null;

    #[ORM\Column(nullable: true)]
    private ?float $percent = null;

    public function __construct()
    {
        $this->questionResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getQuizTaker(): ?User
    {
        return $this->quizTaker;
    }

    public function setQuizTaker(?User $quizTaker): static
    {
        $this->quizTaker = $quizTaker;

        return $this;
    }

    public function getAttempt(): ?int
    {
        return $this->attempt;
    }

    public function setAttempt(int $attempt): static
    {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * @return Collection<int, QuestionResponse>
     */
    public function getQuestionResponses(): Collection
    {
        return $this->questionResponses;
    }

    public function addQuestionResponse(QuestionResponse $questionResponse): static
    {
        if (!$this->questionResponses->contains($questionResponse)) {
            $this->questionResponses->add($questionResponse);
            $questionResponse->setQuizResponse($this);
        }

        return $this;
    }

    public function removeQuestionResponse(QuestionResponse $questionResponse): static
    {
        if ($this->questionResponses->removeElement($questionResponse)) {
            // set the owning side to null (unless already changed)
            if ($questionResponse->getQuizResponse() === $this) {
                $questionResponse->setQuizResponse(null);
            }
        }

        return $this;
    }

    public function getTotalRightAnswers(): ?int
    {
        return $this->totalRightAnswers;
    }

    public function setTotalRightAnswers(?int $totalRightAnswers): static
    {
        $this->totalRightAnswers = $totalRightAnswers;

        return $this;
    }

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(?float $percent): static
    {
        $this->percent = $percent;

        return $this;
    }
}
