<?php
// module/Weather/src/Service/WeatherServiceInterface.php

namespace Weather\Service;

interface WeatherServiceInterface
{
    /**
     * @param string $location City name (e.g., "London")
     * @return array
     * @throws \Exception
     */
    public function getWeatherForLocation(string $location): array;

    /**
     * Retrieves a list of available cities for display.
     * @return array
     */
    public function getAvailableLocations(): array;
}