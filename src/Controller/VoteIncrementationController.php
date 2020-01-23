<?php

namespace App\Controller;

use App\Entity\Score;
use App\Repository\CandidateRepository;
use App\Repository\ElectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VoteIncrementationController extends AbstractController {
    
    /**
     * @Route("/api/vote/{electionId}/{cadidateId}", name="vote", methods={"POST"})
     */
    public function score(int $electionId, int $cadidateId, CandidateRepository $candidate, ElectionRepository $election)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getCurrentUser(); // current user
        $election = $election->findOneById($electionId);
        $users = $election->getUsers();
        $candidate = $candidate->findOneById($cadidateId);

        $voters = [];
        foreach($users as $voter) 
        {
            $voters[] = $voter->getEmail();
        }

        if(in_array($user->getEmail(), $voters)) 
        {
            return new JsonResponse(['response' => 'Vous avez déjà voté'], 401);
        }

        $election->addUser($user);
        $em->persist($election);

        $vote = new Score;
        $vote->setCandidate($candidate);
        $em->persist($vote);
        
        $em->flush();

        return new JsonResponse(['response' => 'Merci d\'avoir voté'], 201);
    }

    private function getCurrentUser()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) 
        {
            return;
        }
        if (!is_object($user = $token->getUser())) 
        {
            return;
        }
        return $user;
    }
 
}