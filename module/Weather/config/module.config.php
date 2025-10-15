<?php
// module/Weather/config/module.config.php

namespace Weather;

use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Http\Client as HttpClient;
use Laminas\Log\LoggerInterface;
use Weather\Controller\IndexController;
use Weather\Service\OpenWeatherMapService;
use Weather\Service\WeatherServiceInterface;

return [
    'router' => [
        'routes' => [
            'weather' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/weather',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'aliases' => [
            WeatherServiceInterface::class => OpenWeatherMapService::class,
        ],
        'factories' => [
            // HttpClient is a concrete class, simple invokable factory works.
            HttpClient::class => InvokableFactory::class, 
            
            // OpenWeatherMapService Factory handles dependencies (Config, Client, Logger)
            OpenWeatherMapService::class => Service\Factory\OpenWeatherMapServiceFactory::class,
            
            // Add a basic Logger (see Step 4.1 for setup)
            LoggerInterface::class => Service\Factory\LoggerFactory::class, 
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'weather' => __DIR__ . '/../view',
        ],
    ],
];