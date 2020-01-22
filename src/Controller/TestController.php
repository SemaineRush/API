<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/")
 * 
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(): Response
    {
        $logger = $this->get('logger');
        $logger->info($this->getDoctrine()->getManager());
        $entityManager = $this->getDoctrine()->getManager();
        var_dump($entityManager);
        die;
        return $this->render('message/new.html.twig', []);
    }
}
