<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AzureController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/azure", name="connect_azure_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to azure!
        return $clientRegistry
            ->getClient('azure') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect();
    }

    /**
     * After going to azure, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/azure/check", name="connect_azure_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, JWTTokenManagerInterface $JWTManager)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\azureClient $client */
        $client = $clientRegistry->getClient('azure');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\azureUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            $dbEmails =  $this->getDoctrine()->getManager()->getRepository('App\\Entity\\User')->findAll();
            if (in_array($user->getUpn(), $dbEmails)) {
                $user = $this->getDoctrine()
                    ->getRepository('App\\Entity\\User')
                    ->findOneByEmail($user->getUpn());
                $user->setIsEnable(TRUE);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(['token' => $JWTManager->create($user)]);
            } else {
                $userNew = new User();
                $userNew->setEmail($user->getUpn());
                $userNew->setPassword('motdepas');
                $userNew->setName($user->getFirstName() . "" . $user->getLastName());
                $userNew->setIsEnable(TRUE);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userNew);
                $entityManager->flush();
                return new JsonResponse(['token' => $JWTManager->create($userNew)]);
            }
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());
            die;
        }
    }
}
