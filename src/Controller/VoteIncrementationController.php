<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteIncrementationController extends AbstractController {
    
    /**
     * @Route("/vote/{id}", name="vote", methods={"POST"})
     */
    public function vote(int $id, CandidateRepository $candidate)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $candidate->findById($id);
        $user->getNbVotes() + 1;

        $em->persist($user);
        $em->flush();

        return new $this->json(['status' => '201', 'response' => 'Merci d\'avoir voté']);
    }
 
}