<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class JWTAuthenticator extends JWTTokenAuthenticator
{
    private $firewallMap;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        TokenExtractorInterface $tokenExtractor,
        FirewallMap $firewallMap
    ) {
        parent::__construct($jwtManager, $dispatcher, $tokenExtractor);

        $this->firewallMap = $firewallMap;
    }

    public function getCredentials(Request $request)
    {
        try {
            return parent::getCredentials($request);
        } catch (AuthenticationException $e) {
            $firewall = $this->firewallMap->getFirewallConfig($request);
            // if anonymous is allowed, do not throw error
            if ($firewall->allowsAnonymous()) {
                return;
            }

            throw $e;
        }
    }
}
