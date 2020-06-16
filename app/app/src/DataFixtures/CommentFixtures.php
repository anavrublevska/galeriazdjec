<?php
/**
 * Comment fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


/**
 * Class CommentFixtures.
 *
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */

    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(50, 'comments', function ($i) {
            $comment = new Comment();
            $comment->setContent($this->faker->sentence);
            $this->manager->persist($comment);
            $comment->setPhoto($this->getRandomReference('photos'));
            $comment->setUser($this->getRandomReference('users'));

            return $comment;
        });


        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [PhotoFixtures::class, UserFixtures::class];
    }

}

