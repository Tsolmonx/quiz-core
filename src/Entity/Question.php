<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'app_question')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionGroup $questionGroup = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: QuestionImage::class)]
    private Collection $images;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $choiceType = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getQuestionGroup(): ?QuestionGroup
    {
        return $this->questionGroup;
    }

    public function setQuestionGroup(?QuestionGroup $questionGroup): static
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
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

    /**
     * @return Collection<int, QuestionImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(QuestionImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setOwner($this);
        }

        return $this;
    }

    public function removeImage(QuestionImage $image): static
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
        return $this->images->filter(function (QuestionImage $image) use ($type) {
            return $image->getType() === $type;
        });
    }

    public function getChoiceType(): ?string
    {
        return $this->choiceType;
    }

    public function setChoiceType(?string $choiceType): static
    {
        $this->choiceType = $choiceType;

        return $this;
    }
}
