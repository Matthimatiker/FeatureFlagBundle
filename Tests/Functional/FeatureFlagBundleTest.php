<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Functional;

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

    }

    public function testDeniesAccessToNotAssignedFeature()
    {

    }

    public function testDeniesAccessToNotExistingFeature()
    {

    }

    public function testGrantsAccessToFeaturesInheritedFromAnotherFeature()
    {

    }

    public function testGrantsAccessToFeatureInheritedFromAnotherRole()
    {

    }

    public function testGrantsAccessToRoleInheritedFromAnotherRole()
    {

    }
}
