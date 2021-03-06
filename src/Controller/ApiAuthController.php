<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
        $data = json_decode($request->getContent(), true);

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'firstname' => new Assert\Length(array('min' => 1)),
            'lastname' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string) $violations], 500);
        }
        
        if (!preg_match("/.+\..+@supinternet\.fr/", $data['email'])) {
            return new JsonResponse(["error" => "This email is not from Sup'Internet"], 500);
        }

        $dbEmails =  $this->getDoctrine()->getManager()->getRepository('App\\Entity\\User')->findAll();
        
        if (in_array($data['email'], $dbEmails)) {
            return new JsonResponse(["error" => "This email has already been registered"], 500);
        }
        
        if (preg_match("/.+[0-9]+.+/", $data['email'])) {
            $emailStrip = preg_replace("/[0-9]+/", "", $data['email']);
            if (in_array($emailStrip, $dbEmails)) {
                return new JsonResponse(["error" => "1 : An email too similar has already been registered, contact an administrator to get further informations"], 500);
            }
        } else {
            foreach ($dbEmails as $dbEmail) {
                $emailStrip = preg_replace("/[0-9]+/", "", $dbEmail);
                if ($emailStrip == $data['email']) {
                    return new JsonResponse(["error" => "2 : An email too similar has already been registered, contact an administrator to get further informations"], 500);
                }
            }
        }

        $username = $data['firstname'] . ' ' . $data['lastname'];
        $password = $data['password'];
        $email = strtolower($data['email']);
        $user = new User();

        $token = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 20);;

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setName($username)
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

        $message = (new \Swift_Message("SUP'Vote - Confirmez vôtre compte"))
            ->setFrom('semainerush.supagency@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'email/confirmation.html.twig',
                    [
                        'confirmationUrl' => $link,
                        'user' => $user
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);


        return new JsonResponse(["success" => $user->getUsername() . " has been registered!"], 200);
    }

    /**
     * @Route("/auth/confirmation/{id}/{token}", methods={"GET"}, name="confirmation")
     */

    public function confirmAccount($id, $token)
    {

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->FindOneBy(['id' => $id, 'token' => $token]);
            
        if ($user->getToken() == $token) {
          $user->setIsEnable(1);
          
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($user);
          $entityManager->flush();

          return $this->redirect('https://supvote.herokuapp.com/');
        } else {
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
            ->findOneByEmail($data['email']);

        if ($user) {
            $strPass = ucfirst(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10));
            $numberPass = substr(str_shuffle('0123456789'), 0, 4);

            $plainPassword = str_shuffle($strPass . $numberPass);
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message("SUP'Vote - Reinitialisation de vôtre mot de passe"))
                ->setFrom('semainerush.supagency@gmail.com')
                ->setTo($data['email'])
                ->setBody(
                    $this->renderView(
                        'email/reset_password.html.twig',
                        ['password' => $plainPassword]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            return new JsonResponse(["status" => (string) "Email send"], 200);
        } else {
            return $this->json(["error" => 'You do not have an account'], 400);
        }
    }
}
