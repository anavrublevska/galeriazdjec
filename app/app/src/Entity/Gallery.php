<?php
/**
 * Gallery entity
 */
namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class gallery.
 *
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 * @ORM\Table(name="galleries")
 *
 * @UniqueEntity(fields={"name_gallery"})
 */
class Gallery
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name of the gallery
     *
     * @ORM\Column(type="string", length=40)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="40",
     * )
     */
    private $name_gallery;

    /**
     * Photos.
     *
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="gallery")
     */
    private $photos;

    /**
     * Gallery constructor.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    /**
     * Getter for id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for the gallery_name.
     *
     * @return string|null
     */
    public function getNameGallery(): ?string
    {
        return $this->name_gallery;
    }

    /**
     *  Setter for name_gallery.
     *
     *  @param string $name_gallery
     */
    public function setNameGallery(string $name_gallery): void
    {
        $this->name_gallery = $name_gallery;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo): void
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setGallery($this);
        }
    }

    /**
     * @param Photo $photo
     */
    public function removePhoto(Photo $photo): void
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getGallery() === $this) {
                $photo->setGallery(null);
            }
        }

    }
}
