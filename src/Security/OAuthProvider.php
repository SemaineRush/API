<?php

namespace App\Security;

use App\Entity\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;

class OAuthProvider
{


    // protected $session, $doctrine, $admins;

    // public function __construct($session, $doctrine, $service_container)
    // {
    //      die('tototo');
    //     $this->session = $session;
    //     $this->doctrine = $doctrine;
    //     $this->container = $service_container;
    // }

    // public function loadUserByUsername($username)
    // {

    //     $qb = $this->doctrine->getManager()->createQueryBuilder();
    //     $qb->select('u')
    //         ->from('User', 'u')
    //         ->where('u.azureId = :gid')
    //         ->setParameter('gid', $username)
    //         ->setMaxResults(1);
    //     $result = $qb->getQuery()->getResult();

    //     if (count($result)) {
    //         return $result[0];
    //     } else {
    //         return new User();
    //     }
    // }

    // public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    // {
    //     //Data from Google response
    //     $google_id = $response->getUsername(); /* An ID like: 112259658235204980084 */
    //     $email = $response->getEmail();
    //     $nickname = $response->getNickname();
    //     $realname = $response->getRealName();

    //     //set data in session
    //     $this->session->set('email', $email);
    //     $this->session->set('nickname', $nickname);
    //     $this->session->set('realname', $realname);

    //     //Check if this Google user already exists in our app DB
    //     $qb = $this->doctrine->getManager()->createQueryBuilder();
    //     $qb->select('u')
    //         ->from('User', 'u')
    //         ->where('u.azureId = :gid')
    //         ->setParameter('gid', $google_id)
    //         ->setMaxResults(1);
    //     $result = $qb->getQuery()->getResult();

    //     //add to database if doesn't exists
    //     if (!count($result)) {
    //         $user = new User();
    //         $user->setName($realname . "" . $nickname);
    //         $user->setEmail($email);
    //         $user->setAzureId($google_id);
    //         //$user->setRoles('ROLE_USER');

    //         //Set some wild random pass since its irrelevant, this is Google login
    //         $factory = $this->container->get('security.encoder_factory');
    //         $encoder = $factory->getEncoder($user);
    //         $password = $encoder->encodePassword(md5(uniqid()), $user->getSalt());
    //         $user->setPassword($password);

    //         $em = $this->doctrine->getManager();
    //         $em->persist($user);
    //         $em->flush();
    //     } else {
    //         $user = $result[0]; /* return User */
    //     }

    //     //set id
    //     $this->session->set('id', $user->getId());

    //     return $this->loadUserByUsername($response->getUsername());
    // }

    // public function supportsClass($class)
    // {
    //     return $class === 'App\\Entity\\User';
    // }
}
