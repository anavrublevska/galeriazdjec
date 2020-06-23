<?php
/**
 * Entity Comment.
 */
namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class comment.
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comments")
 *
 */
class Comment
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Content.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * Photo.
     *
     * @ORM\ManyToOne(targetEntity=Photo::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Getter for id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter for photo.
     *
     * @return Photo|null
     */
    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    /**
     * Setter for photo.
     *
     * @param Photo|null $photo
     */
    public function setPhoto(?Photo $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * Getter for user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
