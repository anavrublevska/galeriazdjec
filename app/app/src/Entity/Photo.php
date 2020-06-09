<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class photo.
 *
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 * @ORM\Table(name="photos")
 */
class Photo
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
     * Link to the gallery.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * Title.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * Description of the photo.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gallery;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="photos",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="photos_tags")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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
     * Getter for link.
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Setter for link.
     *
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;

    }

    /**
     * Getter title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;

    }

    /**
     * Getter description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter description.
     *
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;

    }

    /**
     * Get gallery.
     *
     * @return Gallery|null
     */
    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    /**
     * Set gallery.
     *
     * @param Gallery|null $gallery
     * @return $this
     */
    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

}
