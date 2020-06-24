<?php
/**
 * Fixture for Gallery.
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
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'galleries', function ($i) {
            $gallery = new Gallery();
            $gallery->setNameGallery($this->faker->colorName);
            $this->manager->persist($gallery);

            return $gallery;
        });

        $manager->flush();
    }
}
