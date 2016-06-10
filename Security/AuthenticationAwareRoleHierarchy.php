<?php

namespace Matthimatiker\FeatureFlagBundle\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Role\Role;
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
     * @var RoleHierarchyInterface
     */
    private $innerHierarchy = null;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker = null;

    /**
     * Permissions that are checked and passed to the inner hierarchy (if available).
     *
     * @var string[]
     */
    private $permissions = array(
        AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY,
        AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED,
        AuthenticatedVoter::IS_AUTHENTICATED_FULLY
    );

    /**
     * @param RoleHierarchyInterface $innerHierarchy
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        RoleHierarchyInterface $innerHierarchy,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->innerHierarchy = $innerHierarchy;
        $this->authorizationChecker = $authorizationChecker;
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
        foreach ($this->permissions as $permission) {
            if ($this->authorizationChecker->isGranted($permission)) {
                $roles[] = new Role($permission);
            }
        }
        $reachableRoles = $this->innerHierarchy->getReachableRoles($roles);
        return $this->filterReachableRoles($reachableRoles);
    }

    /**
     * Removes permissions that were passed to the inner hierarchy from the result list.
     *
     * The injected permissions are "artificial" and should not occur in the inherited roles
     * list. Therefore, filtering is necessary.
     *
     * @param RoleInterface[] $reachableRoles
     * @return RoleInterface[]
     */
    private function filterReachableRoles(array $reachableRoles)
    {
        foreach ($reachableRoles as $key => $role) {
            /* @var $role RoleInterface */
            if (in_array($role->getRole(), $this->permissions, true)) {
                unset($reachableRoles[$key]);
            }
        }
        return array_values($reachableRoles);
    }
}
