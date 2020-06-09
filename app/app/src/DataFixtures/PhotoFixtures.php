<?php
/**
 * Photo fixture.
 */
namespace App\DataFixtures;

use App\Entity\Photo;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PhotoFixtures.
 *
 * @package App\DataFixtures
 */
class PhotoFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'photos', function ($i) {
            $photo = new Photo();
            $photo->setTitle($this->faker->word);
            $photo->setDescription($this->faker->sentence);
            $photo->setLink('a.jpeg');
            $photo->setGallery($this->getRandomReference('galleries'));

            return $photo;
        });


        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [GalleryFixtures::class];
    }

}
