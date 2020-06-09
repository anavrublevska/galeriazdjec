<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use App\Repository\PhotoRepository;
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

}
