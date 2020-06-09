<?php
/**
 * Photo fixture.
 */
namespace App\DataFixtures;


use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PhotoFixtures.
 *
 * @package App\DataFixtures
 */
class TagFixtures extends AbstractBaseFixtures
{

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'tags', function ($i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->jobTitle);
            $tag->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $tag->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));

            return $tag;
        });


        $manager->flush();
    }


}
