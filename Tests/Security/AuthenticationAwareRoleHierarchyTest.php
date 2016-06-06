<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Functional;

use Matthimatiker\FeatureFlagBundle\Security\AuthenticationAwareRoleHierarchy;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class AuthenticationAwareRoleHierarchyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var AuthenticationAwareRoleHierarchy
     */
    private $decorator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->decorator = new AuthenticationAwareRoleHierarchy(
            $this->getMock(RoleHierarchyInterface::class),
            $this->getMock(AuthorizationCheckerInterface::class)
        );
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->decorator = null;
        parent::tearDown();
    }

    public function testIsRoleHierarchy()
    {
        $this->assertInstanceOf(RoleHierarchyInterface::class, $this->decorator);
    }

    public function testDoesNotPassAdditionalRolesToInnerHierarchyIfNotAvailable()
    {

    }

    public function testPassesAvailablePermissionsToInnerHierarchy()
    {

    }

    public function testReturnsReachableRolesFromInnerHierarchy()
    {

    }
}
