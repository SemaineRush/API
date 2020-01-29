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
     * @Route(name="current_election", path="/api/election_current", methods={"GET"})
     */
    public function electionCurrent(ElectionRepository $elections): JsonResponse
    {
        $now = new DateTime();

        $election = $elections->findAllOrderByEndDesc()[0];
        $lastElection = [];

        $candidates = $election->getCandidateElection();

        // calcule total score
        $scores = 0;
        foreach ($candidates as $c) {
            $score = count($c->getScores());
            $scores += $score;
        }

        // get number of null votes
        $voters = $election->getUsers();
        $scoreWhite = count($voters) - $scores;
        $scores = $scores + $scoreWhite;

        // candidate score
        $candidatesElection = [];
        foreach ($candidates as $candidate) {
            $candidateScore = count($candidate->getScores());

            $candidatePercentage = null;
            if ($scores != 0) {
                $candidatePercentage = ($candidateScore / $scores) * 100;
            }

            $candidatesElection[$candidate->getUserRelated()->getId()] = [
                "votes" => $candidateScore,
                "idCandidate" => $candidate->getId(),
                "info_candidate" => $candidate->getInformations(),
                "percentage" => $candidatePercentage
            ];
        }

        $lastElection['id'] = $election->getId();
        $lastElection['name'] = $election->getName();
        $lastElection['total_votes'] = $scores;
        $lastElection['white_votes'] = $scoreWhite;

        if ($now > $election->getEndduration()) {
            $lastElection['finished'] = true;
            $lastElection['winner'] = array_keys($candidatesElection, max($candidatesElection))[0];
        } else {
            $lastElection['finished'] = false;
        }

        $lastElection['candidates'] = $candidatesElection;
        $lastElection['start'] = $election->getStart();
        $lastElection['end'] = $election->getEndduration();
        $lastElection['localisation'] = $election->getLocalisation();

        return new JsonResponse(['response' => [
            'last_election' => $lastElection,
        ]], 200);
    }
}
