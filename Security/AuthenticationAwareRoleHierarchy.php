<?php

namespace Matthimatiker\FeatureFlagBundle\Security;

/**
 * A role hierarchy decorator that recognizes IS_AUTHENTICATED_* permissions and allows
 * to assign roles and features to them.
 *
 * The role hierarchy itself must be managed by the decorated hierarchy object, this class
 * just ensures that these permissions are passed to getReachableRoles().
 */
class AuthenticationAwareRoleHierarchy
{

}
