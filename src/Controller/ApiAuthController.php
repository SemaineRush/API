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
    public function register(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
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
        $username = $data['firstname'] . $data['lastname'];
        $password = $data['password'];
        $email = strtolower($data['email']);
        $user = new User();

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setName($username)
            ->setPassword($encoded)
            ->setEmail($email)
            ->setRoles(['ROLE_USER']);
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
        return new JsonResponse(["success" => $user->getUsername() . " has been registered!"], 200);
        // return $this->redirectToRoute('api_auth_login', [
        //     'username' => $data['email'],
        //     'password' => $data['password']
        // ], 307);
    }


    /**
     * @Route(path="api/auth/reset", methods={"POST"}, name="api_auth_reset")
     */
    public function resetPassword(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'oldpassword' => new Assert\Length(array('min' => 1)),
            'newpassword' => new Assert\Length(array('min' => 1)),
            'newpasswordverif' => new Assert\Length(array('min' => 1)),
        ));

        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string) $violations], 500);
        }
        $password = $data['newpassword'];
        return new JsonResponse($password, 200);
    }
}
