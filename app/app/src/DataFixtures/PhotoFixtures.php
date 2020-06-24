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
 */
class PhotoFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Load data photo.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(30, 'photos', function ($i) {
            $photo = new Photo();
            $photo->setTitle($this->faker->word);
            $photo->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $photo->setDescription($this->faker->sentence);
            $input = array("a.jpeg", "b.jpeg", "c.jpeg", "d.jpeg", "e.jpeg", "f.jpeg");
            $numb = rand(0, 5);
            $randy = $input[$numb];
            $photo->setLink($randy);
            $photo->setGallery($this->getRandomReference('galleries'));
            $photo->setAuthor($this->getRandomReference('users'));
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 4)
            );
            foreach ($tags as $tag) {
                $photo->addTag($tag);
            }

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
        return [GalleryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
