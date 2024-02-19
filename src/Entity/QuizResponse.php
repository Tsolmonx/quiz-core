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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToMany(targetEntity: Answer::class)]
    #[ORM\JoinTable(name: 'app_quiz_response_answers')]
    private Collection $selectedAnswers;

    #[ORM\Column]
    private ?int $attempt = null;

    public function __construct()
    {
        $this->selectedAnswers = new ArrayCollection();
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getSelectedAnswers(): Collection
    {
        return $this->selectedAnswers;
    }

    public function addSelectedAnswer(Answer $selectedAnswer): static
    {
        if (!$this->selectedAnswers->contains($selectedAnswer)) {
            $this->selectedAnswers->add($selectedAnswer);
        }

        return $this;
    }

    public function removeSelectedAnswer(Answer $selectedAnswer): static
    {
        $this->selectedAnswers->removeElement($selectedAnswer);

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
}
