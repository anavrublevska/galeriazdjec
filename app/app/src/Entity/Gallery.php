<?php
/**
 * Gallery entity
 */
namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 * @ORM\Table(name="galleries")
 */
class Gallery
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
     * Name of the gallery
     *
     * @ORM\Column(type="string", length=40)
     */
    private $name_gallery;

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
     *
     *  Setter for name_gallery.
     *
     *  @param string $name_gallery
     */

    public function setNameGallery(string $name_gallery): void
    {
        $this->name_gallery = $name_gallery;
    }
}
