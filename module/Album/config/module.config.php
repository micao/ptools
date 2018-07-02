<?php

namespace Album;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;

return [
    'controllers' => array(
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\SuccessController::class => Controller\Factory\SuccessControllerFactory::class,
            Controller\AlbumController::class => Controller\Factory\AlbumControllerFactory::class,
        ],
    ),
    'router' => [
        'routes' => [
            'album' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'login' => array(
                'type' => Literal::class,
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        'controller' => Controller\AuthController::class,
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            'success' => array(
                'type' => Literal::class,
                'options' => array(
                    'route'    => '/success',
                    'defaults' => array(
                        'controller' => Controller\SuccessController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => array(
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),
        'factories' => array(
            'AuthService' => Service\AuthenticationFactory::class,
        ),
    ),
];
