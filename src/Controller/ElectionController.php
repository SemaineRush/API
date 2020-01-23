<?php

namespace App\Controller;

use App\Repository\ElectionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ElectionController extends AbstractController
{
    /**
     * @Route("/api/election_current", methods={"GET"})
     */
    public function electionCurrent(ElectionRepository $elections) : JsonResponse 
    {
        $now = new DateTime();

        $election = $elections->findAllOrderByEndDesc()[0];
        $lastElection = [];
        
        $candidates = $election->getCandidateElection();
        
        $orderedCandidates = [];
        foreach ($candidates as $candidate) 
        {
            $orderedCandidates[$candidate->getUserRelated()->getUsername()] = $candidate->getScore();
        }
        
        $lastElection['id'] = $election->getId();
        $lastElection['name'] = $election->getName();

        if ($now > $election->getEndduration()) 
        {
            $lastElection['finished'] = true;
            $lastElection['winner'] = array_keys($orderedCandidates, max($orderedCandidates))[0];
        }
        else
        {
            $lastElection['finished'] = false;
            $lastElection['candidates'] = $orderedCandidates;
        }

        $lastElection['start'] = $election->getStart();
        $lastElection['end'] = $election->getEndduration();
        $lastElection['localisation'] = $election->getLocalisation();

        return new JsonResponse(['response' => [
            'last_election' => $lastElection,
        ]], 200);
    }
}