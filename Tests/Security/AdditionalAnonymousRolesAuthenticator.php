<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Functional;

use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Decorates the original AnonymousAuthenticationProvider and adds additional roles
 * to anonymous user tokens.
 *
 * These roles can be used to assign features only to guests.
 */
class AdditionalAnonymousRolesAuthenticator implements AuthenticationProviderInterface
{
    /**
     * @param AnonymousAuthenticationProvider $innerProvider
     * @param string[] $additionalRoles
     */
    public function __construct(AnonymousAuthenticationProvider $innerProvider, array $additionalRoles)
    {

    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     * @return TokenInterface An authenticated TokenInterface instance, never null
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @return boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        // TODO: Implement supports() method.
    }
}
