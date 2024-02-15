<?php

namespace App\Entity;

use App\Repository\QuizImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizImageRepository::class)]
#[ORM\Table(name: 'app_quiz_image')]
class QuizImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type = null;

    /** @var \SplFileInfo|null */
    private $file;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Quiz $owner = null;

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

    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }

    public function getOwner(): ?Quiz
    {
        return $this->owner;
    }

    public function setOwner(?Quiz $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
