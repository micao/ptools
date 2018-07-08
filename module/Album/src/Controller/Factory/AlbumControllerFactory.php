<?php

namespace Album\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Album\Controller\AlbumController;
use Album\Model\AlbumTable;
use Album\Model\User\UserTable;

class AlbumControllerFactory
{
    public function __invoke($container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) {
            $container = $container->getServiceLocator();
        }
        $albumTable = $container->get(AlbumTable::class);
        $userTable = $container->get(UserTable::class);
        $authService = $container->get('AuthService');

        return new AlbumController($albumTable, $userTable, $authService);
    }

}
