<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The plain password
     */
    #[ORM\Column(nullable: true)]
    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Quiz::class)]
    private Collection $quizzes;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: UserImage::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'quizTaker', targetEntity: QuizResponse::class)]
    private Collection $quizResponses;

    public function __construct()
    {
        $this->quizzes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->quizResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setCreatedBy($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getCreatedBy() === $this) {
                $quiz->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(UserImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setOwner($this);
        }

        return $this;
    }

    public function removeImage(UserImage $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getOwner() === $this) {
                $image->setOwner(null);
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
            $quizResponse->setQuizTaker($this);
        }

        return $this;
    }

    public function removeQuizResponse(QuizResponse $quizResponse): static
    {
        if ($this->quizResponses->removeElement($quizResponse)) {
            // set the owning side to null (unless already changed)
            if ($quizResponse->getQuizTaker() === $this) {
                $quizResponse->setQuizTaker(null);
            }
        }

        return $this;
    }
}
