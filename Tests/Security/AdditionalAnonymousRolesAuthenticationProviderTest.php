<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Security;

use Matthimatiker\FeatureFlagBundle\Security\AdditionalAnonymousRolesAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class AdditionalAnonymousRolesAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var AdditionalAnonymousRolesAuthenticationProvider
     */
    private $authenticationProvider = null;

    /**
     * @var AnonymousAuthenticationProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $innerAuthenticationProvider = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->innerAuthenticationProvider = $this->getMockBuilder(AnonymousAuthenticationProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->innerAuthenticationProvider->expects($this->any())
            ->method('authenticate')
            ->willReturnArgument(0);
        $this->authenticationProvider = new AdditionalAnonymousRolesAuthenticationProvider(
            $this->innerAuthenticationProvider,
            array('ROLE_ANONYMOUS', 'ROLE_GUEST')
        );
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->authenticationProvider = null;
        $this->innerAuthenticationProvider = null;
        parent::tearDown();
    }

    public function testIsAuthenticator()
    {
        $this->assertInstanceOf(AuthenticationProviderInterface::class, $this->authenticationProvider);
    }

    public function testAcceptsTokenIfInnerAuthenticatorAccepts()
    {
        $this->innerAuthenticationProvider->method('supports')
            ->willReturn(true);

        $this->assertTrue($this->authenticationProvider->supports($this->createToken()));
    }

    public function testRejectsTokenIfInnerAuthenticatorRejects()
    {
        $this->innerAuthenticationProvider->method('supports')
            ->willReturn(false);

        $this->assertFalse($this->authenticationProvider->supports($this->createToken()));
    }

    public function testAddsRolesToAuthenticatedToken()
    {
        $token = $this->authenticationProvider->authenticate($this->createToken());

        $roleNames = $this->getRoleNames($token);
        $this->assertContains('ROLE_ANONYMOUS', $roleNames);
        $this->assertContains('ROLE_GUEST', $roleNames);
    }

    public function testKeepsOriginalRoles()
    {
        $token = $this->authenticationProvider->authenticate($this->createToken());

        $roleNames = $this->getRoleNames($token);
        $this->assertContains('ROLE_ORIGINAL', $roleNames);
    }

    public function testKeepsTokenData()
    {
        $originalToken = $this->createToken();
        $authenticatedToken = $this->authenticationProvider->authenticate($originalToken);

        /* @var $authenticatedToken AnonymousToken */
        $this->assertInstanceOf(AnonymousToken::class, $authenticatedToken);
        $this->assertEquals($originalToken->getSecret(), $authenticatedToken->getSecret());
        $this->assertEquals($originalToken->getUser(), $authenticatedToken->getUser());
    }

    /**
     * Creates an anonymous token for testing.
     *
     * @return AnonymousToken
     */
    private function createToken()
    {
        return new AnonymousToken('my-secret', 'Anonymous user', array('ROLE_ORIGINAL'));
    }

    /**
     * Returns the names of the roles that aare assigned to the given token.
     *
     * @param TokenInterface|mixed $token
     * @return string[]
     */
    private function getRoleNames($token)
    {
        $this->assertInstanceOf(TokenInterface::class, $token);
        $roleNames = array_map(function ($role) {
            /* @var $role RoleInterface */
            $this->assertInstanceOf(RoleInterface::class, $role);
            return $role->getRole();
        }, $token->getRoles());
        return $roleNames;
    }
}
