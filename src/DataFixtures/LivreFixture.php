<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LivreFixture extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<5;$i++){
            $user = new User();
            $user->setNom($faker->lastname)
            ->setPrenom($faker->firstname)
            ->setBirthDate($faker->dateTimeBetween($startDate='-30 years',$endDate='now'))
            ->setAdresse($faker->streetAdress)
            ->setCodePostal($faker->postCode)
            ->setEmail($faker->email)
            ->setPassword($this->password->encoderPassword($user,'azerty')) 
            ->setAvatar("http://picsum.photo/200/300");
            $manager->persist($user);

            for($k=0;$k<3;$k++){
                $category = new Category();
                $category->setNom($faker->safeColorName);
                $manager->persist($category);
                
                for($j=0;$j<rand(2,4);$j++){
                    $livre = new Livre();
                    $livre->setAuteur($faker->name)
                        ->setTitre($faker->title)
                        ->setDateSortie($faker->dateTimeBetween/* ($startDate='-30 years',$endDate='now') */);
                    $livre->setUser($user);
                    $livre->setCategory($category);
                    $manager->persist($livre);
                }
            }
        }
        $manager->flush();
    }
}
