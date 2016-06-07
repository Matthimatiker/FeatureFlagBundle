<?php

namespace Matthimatiker\FeatureFlagBundle\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Decorates the original AnonymousAuthenticationProvider and adds additional roles
 * to anonymous user tokens.
 *
 * These roles can be used to assign features only to guests.
 *
 * Notice: This class can only decorate AnonymousAuthenticationProvider objects as it relies
 * on working with AnonymousToken instances.
 */
class AdditionalAnonymousRolesAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var AnonymousAuthenticationProvider
     */
    private $innerProvider = null;

    /**
     * Roles that will be added for anonymous users.
     *
     * @var array<string|RoleInterface>
     */
    private $additionalRoles = null;

    /**
     * @param AnonymousAuthenticationProvider $innerProvider
     * @param array<string|RoleInterface> $additionalRoles
     */
    public function __construct(AnonymousAuthenticationProvider $innerProvider, array $additionalRoles)
    {
        $this->innerProvider = $innerProvider;
        $this->additionalRoles = $additionalRoles;
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
        /* @var $authenticatedToken AnonymousToken */
        $authenticatedToken = $this->innerProvider->authenticate($token);
        return $this->addRolesTo($authenticatedToken);
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @return boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $this->innerProvider->supports($token);
    }

    /**
     * @param AnonymousToken $token
     * @return AnonymousToken Token with additional roles.
     */
    private function addRolesTo(AnonymousToken $token)
    {
        return new AnonymousToken(
            $token->getSecret(),
            $token->getUser(),
            $this->mergeRoles($token->getRoles(), $this->additionalRoles)
        );
    }

    /**
     * Merges original and additional roles.
     *
     * @param array<string|RoleInterface> $originalRoles
     * @param array<string|RoleInterface> $additionalRoles
     * @return array<string|RoleInterface>
     */
    private function mergeRoles(array $originalRoles, array $additionalRoles)
    {
        return array_merge(array_values($originalRoles), array_values($additionalRoles));
    }
}
