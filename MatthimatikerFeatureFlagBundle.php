<?php

namespace Matthimatiker\FeatureFlagBundle;

use Matthimatiker\FeatureFlagBundle\DependencyInjection\Compiler\DecorateAnonymousAuthenticationProvidersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MatthimatikerFeatureFlagBundle extends Bundle
{
    /**
     * Registers compiler passes.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DecorateAnonymousAuthenticationProvidersPass());
    }
}
