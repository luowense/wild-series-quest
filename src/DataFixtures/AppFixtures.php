<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Season;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $content = '<p>' . join($faker->paragraph(5), '</p><p>') . '</p>';
        //episodes creation
        for ($i = 1; $i <= 10; $i++) {


            $episode = new Episode();
            $episode->setTitle($faker->sentence())
                ->setSynopsys($content)
                ->setNumber(numberBetween(1,20))
                ->setSeasonId();

            $manager->persist($episode);
        }

        //seasons creation
        for ($j = 1; $j <= 5; $j++) {
            $season = new Season();
            $season->setYear($faker->year())
                ->setDescription($content)
                ->setSeason($faker->numberBetween(1, 20));

            $manager->persist($season);
        }

        //comments creation
        for ($x = 1; $x <= 10; $x++) {
            $comment = new Comment();
            $comment->setUserId($faker->numberBetween(1,200))
                ->setComment($content)
                ->setRate($faker->numberBetween(1, 100))
                ->setEpisodeId(1);

            $manager->persiste($comment);
        }




        $manager->flush();
    }
}
