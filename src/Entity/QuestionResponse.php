<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionResponseRepository::class)]
#[ORM\Table(name: 'app_question_response')]
#[ApiResource]
class QuestionResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToMany(targetEntity: Answer::class)]
    #[ORM\JoinTable(name: 'app_question_response_answers')]
    private Collection $selectedAnswers;

    #[ORM\ManyToOne(inversedBy: 'questionResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuizResponse $quizResponse = null;

    #[ORM\Column]
    private ?bool $isCorrect = null;

    #[ORM\ManyToMany(targetEntity: Answer::class)]
    #[ORM\JoinTable(name: 'app_question_response_right_answers')]
    private Collection $rightAnswers;

    public function __construct()
    {
        $this->selectedAnswers = new ArrayCollection();
        $this->rightAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuizResponse(): ?QuizResponse
    {
        return $this->quizResponse;
    }

    public function setQuizResponse(?QuizResponse $quizResponse): static
    {
        $this->quizResponse = $quizResponse;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getRightAnswers(): Collection
    {
        return $this->rightAnswers;
    }

    public function addRightAnswer(Answer $rightAnswer): static
    {
        if (!$this->rightAnswers->contains($rightAnswer)) {
            $this->rightAnswers->add($rightAnswer);
        }

        return $this;
    }

    public function removeRightAnswer(Answer $rightAnswer): static
    {
        $this->rightAnswers->removeElement($rightAnswer);

        return $this;
    }
}
