<?php
// module/Weather/src/Service/Factory/LoggerFactory.php

namespace Weather\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class LoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Logger
    {
        $logger = new Logger();
        // Log to a file in the data/log directory (Laminas standard)
        $writer = new Stream('data/log/app.log'); 
        $logger->addWriter($writer);
        return $logger;
    }
}