<?php

namespace Matthimatiker\FeatureFlagBundle\Tests\Functional;

use Matthimatiker\FeatureFlagBundle\MatthimatikerFeatureFlagBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Minimal kernel for testing.
 */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @param string $environment The environment
     * @param bool $debug Whether to enable debugging or not
     */
    public function __construct($environment = 'test', $debug = true)
    {
        parent::__construct($environment, $debug);
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new SecurityBundle(),
            new MatthimatikerFeatureFlagBundle()
        );
    }

    /**
     * Add or import routes into your application.
     *
     *     $routes->import('config/routing.yml');
     *     $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
     *
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
    }

    /**
     * Configures the container.
     *
     * You can register extensions:
     *
     * $c->loadFromExtension('framework', array(
     *     'secret' => '%secret%'
     * ));
     *
     * Or services:
     *
     * $c->register('halloween', 'FooBundle\HalloweenProvider');
     *
     * Or parameters:
     *
     * $c->setParameter('halloween', 'lot of fun');
     *
     * @param ContainerBuilder $c
     * @param LoaderInterface $loader
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', ['secret' => 'any-secret']);
        $loader->load(__DIR__.'/_files/config/security.yml');
    }

    /**
     * Boots the current kernel.
     */
    public function boot()
    {
        if (!$this->booted) {
            $this->deleteCache();
        }
        parent::boot();
    }

    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
        if (!$this->booted) {
            return;
        }
        parent::shutdown();
        $this->deleteCache();
    }

    /**
     * Gets the cache directory.
     *
     * @return string The cache directory
     */
    public function getCacheDir()
    {
        return __DIR__ . '/_files/cache';
    }

    /**
     * Simulates authentication as the provided user.
     *
     * Pass null to authenticate as anonymous user.
     *
     * @param UserInterface|null $user
     */
    public function authenticateAs(UserInterface $user = null)
    {
        if ($user === null) {
            $token = new AnonymousToken('any-secret', 'anon.');
        } else {
            $token = new UsernamePasswordToken($user, 'any-password', 'test_provider', $user->getRoles());
        }
        $this->getTokenStorage()->setToken($token);
    }

    /**
     * Returns the authorization checker that is used to test the permissions of the logged in user.
     *
     * @return AuthorizationCheckerInterface
     */
    public function getAuthorizationChecker()
    {
        return $this->getContainer()->get('security.authorization_checker');
    }

    /**
     * Removes the whole cache directory.
     */
    private function deleteCache()
    {
        (new Filesystem())->remove($this->getCacheDir());
    }

    /**
     * @return TokenStorageInterface
     */
    private function getTokenStorage()
    {
        return $this->getContainer()->get('security.token_storage');
    }
}
