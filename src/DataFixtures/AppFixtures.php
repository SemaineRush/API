<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Election;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * Encodeur de mot de passe
     *
     * @param UserPasswordEncoderInterface $encoder
     */

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        
        $faker=Factory::create("fr_FR");
        for ($i=0; $i < 10; $i++) { 
            $user=new User;
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash);
                $manager->persist($user);
            
            $candidate=new Candidate;
            $candidate->setInfos($faker->text)
                      ->setStylesheet("body{text-align:center}")
                      ->setUserRelated($user);


            $election=new Election;


        }

        $manager->flush();
    }
}
