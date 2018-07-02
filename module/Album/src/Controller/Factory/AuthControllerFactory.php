<?php

namespace Album\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Album\Controller\AuthController;

class AuthControllerFactory
{
    public function __invoke($container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) {
            $container = $container->getServiceLocator();
        }
        $authService = $container->get('AuthService');

        return new AuthController($authService, $authService->getStorage());
    }

}
