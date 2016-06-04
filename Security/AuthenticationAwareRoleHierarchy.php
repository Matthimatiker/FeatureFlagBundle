<?php

namespace Matthimatiker\FeatureFlagBundle\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * A role hierarchy decorator that recognizes IS_AUTHENTICATED_* permissions and allows
 * to assign roles and features to them.
 *
 * The role hierarchy itself must be managed by the decorated hierarchy object, this class
 * just ensures that these permissions are passed to getReachableRoles().
 */
class AuthenticationAwareRoleHierarchy implements RoleHierarchyInterface
{
    /**
     * @param RoleHierarchyInterface $innerHierarchy
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        RoleHierarchyInterface $innerHierarchy,
        AuthorizationCheckerInterface $authorizationChecker
    ) {

    }

    /**
     * Returns an array of all reachable roles by the given ones.
     *
     * Reachable roles are the roles directly assigned but also all roles that
     * are transitively reachable from them in the role hierarchy.
     *
     * @param RoleInterface[] $roles An array of directly assigned roles
     *
     * @return RoleInterface[] An array of all reachable roles
     */
    public function getReachableRoles(array $roles)
    {
        // TODO: Implement getReachableRoles() method.
    }
}
