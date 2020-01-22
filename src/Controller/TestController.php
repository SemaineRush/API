<?php

namespace App\Controller;

use App\Entity\Election;
use App\Repository\MessageRepository;
use App\Repository\CandidateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/")
 * 
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(CandidateRepository $testsam): Response
    {
        // $sam = new Election;
        // $d = new \DateTime('2011-01-01T15:03:01.012345Z');
        // $sam->setEndtest($d);
        // $sam->setName('toto');
        // $sam->setLocalisation('toto');
        // $sam->setStart($d);
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($sam);
        // $entityManager->flush();
        return $this->render('email/base.html.twig', []);
    }
}
