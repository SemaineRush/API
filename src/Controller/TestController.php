<?php

namespace App\Controller;

use OneSignal\Config;
use OneSignal\Devices;
use App\Entity\Election;
use OneSignal\OneSignal;
use App\Repository\MessageRepository;
use Nyholm\Psr7\Factory\Psr17Factory;
use App\Repository\CandidateRepository;
use App\Service\NotificationSender;
use Symfony\Component\HttpClient\Psr18Client;
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
    public function index(NotificationSender $notif): Response
    {
        // $notif->sendNotif('Fin de l\' élection');
        return $this->render('email/base.html.twig', []);
    }

    /**
     * @Route("/signin", name="signin", methods={"GET"})
     */
    public function signin()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $_ENV('OAUTH_APP_ID'),
            'clientSecret'            => $_ENV('OAUTH_APP_PASSWORD'),
            'redirectUri'             => $_ENV('OAUTH_REDIRECT_URI'),
            'urlAuthorize'            => $_ENV('OAUTH_AUTHORITY') . $_ENV('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken'          => $_ENV('OAUTH_AUTHORITY') . $_ENV('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => '',
            'scopes'                  => $_ENV('OAUTH_SCOPES')
        ]);

        // Generate the auth URL
        $authorizationUrl = $oauthClient->getAuthorizationUrl();

        // Save client state so we can validate in response
        $_SESSION['oauth_state'] = $oauthClient->getState();

        // Redirect to authorization endpoint
        header('Location: ' . $authorizationUrl);
        exit();
    }

    /**
     * @Route("/authorize", name="authorize", methods={"GET"})
     */
    public function gettoken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Authorization code should be in the "code" query param
        if (isset($_GET['code'])) {
            echo 'Auth code: ' . $_GET['code'];
            exit();
        } elseif (isset($_GET['error'])) {
            exit('ERROR: ' . $_GET['error'] . ' - ' . $_GET['error_description']);
        }
    }
}
