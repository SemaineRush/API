<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Election;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $faker = Factory::create("fr_FR");
        $votants = [];
        $elections = [];
        $candidates = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User;
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setName($faker->firstName())
                ->setEmail($faker->email)
                ->setPassword($hash);
            $manager->persist($user);
            if ($i < 3) {
                $candidate = new Candidate;
                $candidate->setInformations(["lkfd,b"=>"mle;gml"])
                    ->setStylesheet("body{text-align:center}")
                    ->setUserRelated($user);
                $candidates[] = $candidate;
                $manager->persist($candidate);
            } else {
                $votants[] = $user;
            }

            $election = new Election;
            $election->setEndduration($faker->dateTimeBetween("-1 months"))
                ->setStart($faker->dateTimeBetween("-3 months"))
                ->setLocalisation("Paris")
                ->setName("election BDE");
            $manager->persist($election);
            $elections[] = $election;
        }
        foreach ($elections as $election) {
            $firstVotant = $votants[mt_rand(0, 3)];
            $secondVotant = $votants[mt_rand(3, 6)];
            $firstCandidate = $candidates[mt_rand(0, 1)];
            $secondCandidate = $candidates[2];
            $election->addUser($firstVotant);
            $election->addUser($secondVotant);
            $election->addCandidateElection($firstCandidate);
            $election->addCandidateElection($secondCandidate);
        }
        $manager->flush();
        $user = new User();
        $plainPassword = 'motdepas';
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
        $user
            ->setEmail('sam@sam.fr')
            ->setName('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($encoded);

        $manager->persist($user);
        $manager->flush();
    }
}
