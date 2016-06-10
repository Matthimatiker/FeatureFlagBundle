<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Security;

use Matthimatiker\FeatureFlagBundle\Security\AuthenticationAwareRoleHierarchy;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class AuthenticationAwareRoleHierarchyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var AuthenticationAwareRoleHierarchy
     */
    private $decorator = null;

    /**
     * @var RoleHierarchyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $innerHierarchy = null;

    /**
     * @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $authorizationChecker = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->innerHierarchy = $this->getMock(RoleHierarchyInterface::class);
        $this->authorizationChecker = $this->getMock(AuthorizationCheckerInterface::class);
        $this->decorator = new AuthenticationAwareRoleHierarchy(
            $this->innerHierarchy,
            $this->authorizationChecker
        );
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->decorator = null;
        $this->authorizationChecker = null;
        $this->innerHierarchy = null;
        parent::tearDown();
    }

    public function testIsRoleHierarchy()
    {
        $this->assertInstanceOf(RoleHierarchyInterface::class, $this->decorator);
    }

    public function testDoesNotPassAdditionalRolesToInnerHierarchyIfNotAvailable()
    {
        $this->authorizationChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(false);
        $this->innerHierarchy->expects($this->once())
            ->method('getReachableRoles')
            ->with($this->callback(function (array $roles) {
                $roleNames = $this->getRoleNames($roles);
                $this->assertNotContains(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY, $roleNames);
                $this->assertNotContains(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED, $roleNames);
                $this->assertNotContains(AuthenticatedVoter::IS_AUTHENTICATED_FULLY, $roleNames);
                return true;
            }))
            ->willReturn(array());

        $this->decorator->getReachableRoles(array());
    }

    public function testPassesAvailablePermissionsToInnerHierarchy()
    {
        $this->authorizationChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(true);
        $this->innerHierarchy->expects($this->once())
            ->method('getReachableRoles')
            ->with($this->callback(function (array $roles) {
                $roleNames = $this->getRoleNames($roles);
                $this->assertContains(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY, $roleNames);
                $this->assertContains(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED, $roleNames);
                $this->assertContains(AuthenticatedVoter::IS_AUTHENTICATED_FULLY, $roleNames);
                return true;
            }))
            ->willReturn(array());

        $this->decorator->getReachableRoles(array());
    }

    public function testReturnsReachableRolesFromInnerHierarchy()
    {
        $this->authorizationChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(true);
        $this->innerHierarchy->expects($this->once())
            ->method('getReachableRoles')
            ->willReturn(array(new Role('ROLE_TEST')));

        $roles = $this->decorator->getReachableRoles(array());

        $names = $this->getRoleNames($roles);
        $this->assertContains('ROLE_TEST', $names);
    }

    public function testFiltersIsAuthenticatedPermissionsFromInnerHierarchy()
    {
        $this->authorizationChecker->expects($this->any())
            ->method('isGranted')
            ->willReturn(true);
        $this->innerHierarchy->expects($this->once())
            ->method('getReachableRoles')
            ->willReturn(array(new Role(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY)));

        $roles = $this->decorator->getReachableRoles(array());

        $names = $this->getRoleNames($roles);
        $this->assertNotContains(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY, $names);
    }

    /**
     * @param RoleInterface[]|mixed $roles
     * @return string[]
     */
    private function getRoleNames($roles)
    {
        $this->assertInternalType('array', $roles);
        $names = array();
        foreach ($roles as $role) {
            /* @var $role RoleInterface */
            $this->assertInstanceOf(RoleInterface::class, $role);
            $names[] = $role->getRole();
        }
        return $names;
    }
}
