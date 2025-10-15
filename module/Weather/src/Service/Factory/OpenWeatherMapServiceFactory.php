<?php
// module/Weather/src/Service/Factory/OpenWeatherMapServiceFactory.php

namespace Weather\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Http\Client;
use Laminas\Log\LoggerInterface;
use Weather\Service\OpenWeatherMapService;

class OpenWeatherMapServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): OpenWeatherMapService
    {
        return new OpenWeatherMapService(
            $container->get('Config'),
            $container->get(Client::class),
            $container->get(LoggerInterface::class)
        );
    }
}