<?php

namespace App\Entity;

use App\Repository\QuestionImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionImageRepository::class)]
#[ORM\Table(name: 'app_question_image')]
class QuestionImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Question $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?Question
    {
        return $this->owner;
    }

    public function setOwner(?Question $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
