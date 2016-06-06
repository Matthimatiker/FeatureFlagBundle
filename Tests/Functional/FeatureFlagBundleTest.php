<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Functional;

use Symfony\Component\Security\Core\User\User;

/**
 * This test case relies on the role hierarchy that is defined in _files/config/security.yml.
 *
 * The test case does *not* depend on the users that are defined in the config file.
 */
class FeatureFlagBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestKernel
     */
    private $kernel = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->kernel = new TestKernel();
        $this->kernel->boot();
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->kernel->shutdown();
        $this->kernel = null;
        parent::tearDown();
    }

    public function testGrantsAccessToAssignedFeature()
    {
        $this->authenticateAsUser();

        $this->assertGranted('FEATURE_APP');
    }

    public function testDeniesAccessToNotAssignedFeature()
    {
        $this->authenticateAsUser();

        $this->assertDenied('FEATURE_ADMIN_DASHBOARD');
    }

    public function testDeniesAccessToNotExistingFeature()
    {
        $this->authenticateAsUser();

        $this->assertDenied('FEATURE_MISSING');
    }

    public function testGrantsAccessToFeaturesInheritedFromAnotherFeature()
    {
        $this->authenticateAsAdmin();

        $this->assertGranted('FEATURE_LIST_USERS');
    }

    public function testGrantsAccessToFeatureInheritedFromAnotherRole()
    {
        $this->authenticateAsAdmin();

        $this->assertGranted('FEATURE_APP');
    }

    public function testGrantsAccessToRoleInheritedFromAnotherRole()
    {
        $this->authenticateAsAdmin();

        $this->assertGranted('ROLE_USER');
    }

    public function testGrantsAccessToFeaturesThatAreAssignedToAnonymous()
    {
        $this->authenticateAnonymously();

        $this->assertGranted('FEATURE_FOR_ALL');
    }

    public function testDeniesAccessToAnonymousFeaturesForLoggedInUser()
    {
        $this->authenticateAsUser();

        $this->assertGranted('FEATURE_FOR_ALL');
    }

    public function testDeniesAccessToUserFeaturesForGuests()
    {
        $this->authenticateAnonymously();

        $this->assertDenied('FEATURE_APP');
    }

    private function authenticateAsUser()
    {
        $this->kernel->authenticateAs(new User('test', 'any-password', array('ROLE_USER')));
    }

    private function authenticateAsAdmin()
    {
        $this->kernel->authenticateAs(new User('test', 'any-password', array('ROLE_ADMIN')));
    }

    private function authenticateAnonymously()
    {
        $this->kernel->authenticateAs(null);
    }

    /**
     * @param string $role
     */
    private function assertGranted($role)
    {
        $this->assertTrue(
            $this->kernel->getAuthorizationChecker()->isGranted($role),
            'The logged in user does not have access to "' . $role . '"."'
        );
    }

    /**
     * @param string $role
     */
    private function assertDenied($role)
    {
        $this->assertFalse(
            $this->kernel->getAuthorizationChecker()->isGranted($role),
            'The logged in user has access to "' . $role . '"."'
        );
    }
}
