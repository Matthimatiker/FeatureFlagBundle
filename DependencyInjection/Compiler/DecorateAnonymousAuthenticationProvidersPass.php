<?php

namespace Matthimatiker\FeatureFlagBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Decorates the configured AnonymousAuthenticationProvider to ensure that anonymous
 * users (guests) retrieve additional roles.
 */
class DecorateAnonymousAuthenticationProvidersPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getServiceIds() as $id) {
            if (!$this->isAnonymousAuthenticationProvider($id)) {
                continue;
            }
            $decoratorId = 'matthimatiker_feature_flag.guest_roles.authentication_provider.%s';
            $decoratorId = sprintf($decoratorId, $this->getAnonymousAuthenticationProviderName($id));
            // The DefinitionDecorator is used as we want to copy the parent definition.
            $decorator = new DefinitionDecorator('matthimatiker_feature_flag.guest_roles.authentication_provider');
            // Configure it to decorate the original provider (in this case the decorator pattern is meant).
            $decorator->setDecoratedService($id);
            $decorator->replaceArgument(0, new Reference($decoratorId . '.inner'));
            $container->setDefinition($decoratorId, $decorator);
        }
    }

    /**
     * Checks if the given service ID belongs to an AnonymousAuthenticationProvider.
     *
     * @param string $id
     * @return boolean
     */
    private function isAnonymousAuthenticationProvider($id)
    {
        return strpos($id, 'security.authentication.provider.anonymous.') === 0;
    }

    /**
     * Returns the name of the AnonymousAuthentication with the given ID.
     *
     * The name corresponds to the name/id/provider-key of the firewall that the provider
     * is assigned to.
     *
     * @param string $id
     * @return string
     */
    private function getAnonymousAuthenticationProviderName($id)
    {
        return ltrim(strrchr($id, '.'), '.');
    }
}
