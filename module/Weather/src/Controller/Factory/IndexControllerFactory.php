<?php
// module/Weather/src/Controller/Factory/IndexControllerFactory.php

namespace Weather\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Weather\Controller\IndexController;
use Weather\Service\WeatherServiceInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IndexController
    {
        return new IndexController(
            $container->get(WeatherServiceInterface::class)
        );
    }
}