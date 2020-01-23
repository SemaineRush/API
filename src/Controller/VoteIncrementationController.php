<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use App\Repository\ElectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteIncrementationController extends AbstractController {
    
    /**
     * @Route("/vote/{electionId}/{cadidateId}", name="vote", methods={"POST"})
     */
    public function vote(int $electionId, int $cadidateId, CandidateRepository $candidate, ElectionRepository $election)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getCurrentUser(); // current user
        $election = $election->findById($electionId);
        $voters = $election->getUsers();

        if(in_array($user, $voters)) 
        {
            return new $this->json(['status' => '401', 'response' => 'Vous avez déjà voté']);
        }

        $election->addUser($user);
        $em->persist($election);

        $candidate = $candidate->findById($cadidateId);
        $candidate->getNbVotes() + 1;
        $em->persist($candidate);
        
        $em->flush();

        return new $this->json(['status' => '201', 'response' => 'Merci d\'avoir voté']);
    }

    private function getCurrentUser()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }
        if (!is_object($user = $token->getUser())) {
            return;
        }
        return $user;
    }
 
}