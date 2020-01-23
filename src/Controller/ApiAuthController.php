<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ApiAuthController extends AbstractController
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @Route(path="api/auth/register", methods={"POST"}, name="api_auth_register")
     */
    public function register(Request $request, \Swift_Mailer $mailer)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
        ));

        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string) $violations], 500);
        }
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $user = new User();

        $token = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 20);;

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setUsername($username)
            ->setPassword($encoded)
            ->setEmail($email)
            ->setRoles(['ROLE_USER'])
            ->setIsEnable(0)
            ->setToken($token);
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        $link = "https://testsamheroku.herokuapp.com/auth/confirmation/{$user->getId()}/{$user->getToken()}";

        $message = (new \Swift_Message("SUP'Vote - Confirmer vôtre compte"))
            ->setFrom('semainerush.supagency@gmail.com')
            ->setTo('decobert.a78@gmail.com')
            ->setBody(
              $this->renderView(
                'email/confirmation.html.twig',
                [
                  'confirmationUrl' => $link,
                  'user' => $user
                ]
              ), 'text/html');

        $mailer->send($message);


        return new JsonResponse(["success" => $user->getUsername() . " has been registered!"], 200);
        // return $this->redirectToRoute('api_auth_login', [
        //     'username' => $data['email'],
        //     'password' => $data['password']
        // ], 307);
    }

    /**
     * @Route("/auth/confirmation/{id}/{token}", methods={"GET"}, name="confirmation")
     */

     public function confirmAccount($id, $token)
     {


        $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->FindOneBy(['id' => $id, 'token' => $token]);
        if($user) {
          $user->setIsEnable(1);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($user);
          $entityManager->flush();

          return new JsonResponse(['success' => 'Account is Enable'], 200);
        }else {
          return new JsonResponse(['error' => 'Account is not Enable'], 400);
        }
        
     }
    /**
     * @Route(path="api/auth/reset", methods={"POST"}, name="api_auth_reset")
     */
    public function resetPassword(Request $request, \Swift_Mailer $mailer)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->FindOneByEmail($data['email']);

        if($user){
            $plainPassword = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 10);;
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('semainerush.supagency@gmail.com')
                ->setTo('decobert.a78@gmail.com')
                ->setBody(
                  $this->renderView(
                    'email/reset_password.html.twig',
                    ['password' => $plainPassword]
                  ), 'text/html');

            $mailer->send($message);
            return new JsonResponse(["status" => (string) "Email send"], 200);
        }else {
            return $this->json(["lol" => 'haha']);
        }
        // return new JsonResponse($user, 200);
    }
}
