<?php

namespace Album\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Album\Controller\SuccessController;

class SuccessControllerFactory
{
    public function __invoke($container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) {
            $container = $container->getServiceLocator();
        }
        $authService = $container->get('AuthService');

        return new SuccessController($authService);
    }

}
