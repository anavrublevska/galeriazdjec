<?php
/**
 * fixture for Gallery
 */
namespace App\DataFixtures;

use App\Entity\Gallery;
use Doctrine\Persistence\ObjectManager;

/**
 * Class GalleryFixtures.
 */
class GalleryFixtures extends AbstractBaseFixtures
{

    /**
     * Adding gallery name to the table gallery.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $gallery = new Gallery();
            $gallery->setNameGallery($this->faker->colorName);
            $this->manager->persist($gallery);
        }

        $manager->flush();
    }

}
