<?php

namespace App\Entity;

use App\Repository\AnswerImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerImageRepository::class)]
#[ORM\Table(name: 'app_answer_image')]
class AnswerImage
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
    private ?Answer $owner = null;

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

    public function getOwner(): ?Answer
    {
        return $this->owner;
    }

    public function setOwner(?Answer $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
