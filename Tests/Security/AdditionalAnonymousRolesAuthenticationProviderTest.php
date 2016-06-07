<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Security;

use Matthimatiker\FeatureFlagBundle\Security\AdditionalAnonymousRolesAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

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
        $this->authenticationProvider = new AdditionalAnonymousRolesAuthenticationProvider(
            $this->innerAuthenticationProvider,
            array('ROLE_ANONYMOUS')
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

    }

    public function testRejectsTokenIfInnerAuthenticatorRejects()
    {

    }

    public function testAddsRolesToAuthenticatedToken()
    {

    }
}
