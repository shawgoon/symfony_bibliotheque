<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LivreFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \faker\Factory::create('fr_FR');
        for($i=0;$i<5;$i++){
            $user = new User();
            $user->setNom($faker->lastname())
                ->setPrenom($faker->firstname())
                ->setBirthDate($faker->dateTimeBetween($startDate='-30 years',$endDate='now'))
                ->setAdresse($faker->streetAdress())
                ->setCodePostal($faker->postCode())
                ->setEmail($faker->email())
                ->setPassword('azerty')
                ->setAvatar("http://picsum.photo/200/300");
            $manager->persist($user);
            for($j=0;$j<rand(2,4);$j++){
                $livre = new Livre();
                $livre->setAuteur($faker->name)
                    ->setTitre($faker->title)
                    ->setDateSortie($faker->dateTimeBetween($startDate='-30 years',$endDate='now'));
                $livre->setUser($user);
                $manager->persist($livre);
            }
        }
        $manager->flush();
    }
}
