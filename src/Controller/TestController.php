<?php

namespace App\Controller;

use OneSignal\Config;
use OneSignal\Devices;
use App\Entity\Election;
use OneSignal\OneSignal;
use App\Repository\MessageRepository;
use Nyholm\Psr7\Factory\Psr17Factory;
use App\Repository\CandidateRepository;
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
    public function index(CandidateRepository $testsam): Response
    {
        $config = new Config('5ba848ee-3147-4157-a4d9-83cf5d4186b9', 'MDc4MDQ5MWYtNjg2MS00ZTk2LThjM2MtNzUwYjEwZTZjODFi', 'MDlmMjJmZDEtMzNlZi00YjNjLTlkNGItMTM3ZmFlNmE2ZjA0');
        // $config = new Config('d66405ab-347d-4c80-9ecd-2f5684f0f55b', 'ZjQzZmRmMzktODNkNS00NDU5LWE5MTUtN2E1Yjg4NjBhODU2', 'MDlmMjJmZDEtMzNlZi00YjNjLTlkNGItMTM3ZmFlNmE2ZjA0');
        $httpClient = new Psr18Client();
        $requestFactory = $streamFactory = new Psr17Factory();

        $oneSignal = new OneSignal($config, $httpClient, $requestFactory, $streamFactory);

        // $myApp = $oneSignal->apps()->getOne('d66405ab-347d-4c80-9ecd-2f5684f0f55b');
        // $oneSignal->apps()->createSegment('d66405ab-347d-4c80-9ecd-2f5684f0f55b', [
        //     'name' => 'Test Sam',
        //     'filters' => [
        //         ['field' => 'session_count', 'relation' => '>', 'value' => 1],
        //         ['operator' => 'AND'],
        //         ['field' => 'tag', 'relation' => '!=', 'key' => 'tag_key', 'value' => '1'],
        //         ['operator' => 'OR'],
        //         ['field' => 'last_session', 'relation' => '<', 'value' => '30,'],
        //     ],
        // ]);
        // $oneSignal->notifications()->add([
        //     'contents' => [
        //         'en' => 'Notification message'
        //     ],
        //     'included_segments' => ['All'],
        //     'data' => ['foo' => 'bar'],
        //     'isChrome' => true,
        //     'send_after' => new \DateTime('1 min'),
        //     'filters' => [
        //         [
        //             'field' => 'tag',
        //             'key' => 'is_vip',
        //             'relation' => '!=',
        //             'value' => 'true',
        //         ],
        //         [
        //             'operator' => 'OR',
        //         ],
        //         [
        //             'field' => 'tag',
        //             'key' => 'is_admin',
        //             'relation' => '=',
        //             'value' => 'true',
        //         ],
        //     ],
        // ]);
        // var_dump($oneSignal->notifications()->getAll());
        // die;
        $oneSignal->notifications()->add([
            'contents' => [
                'fr' => "L'élection vient de se terminer , viens consulter les résultats"
            ],
            'included_segments' => ['All'],
            'url' => "https://supvote.herokuapp.com/currentelection",
            'isAnyWeb' => true,
            'filters' => [],
        ]);
        var_dump($oneSignal);
        // die;
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
