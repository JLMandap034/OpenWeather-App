<?php
// module/Weather/src/Service/OpenWeatherMapService.php

namespace Weather\Service;

use Laminas\Http\Client;
use Laminas\Log\LoggerInterface;

class OpenWeatherMapService implements WeatherServiceInterface
{
    private $config;
    private $httpClient;
    private $logger;
    private $locations;

    public function __construct(array $config, Client $httpClient, LoggerInterface $logger)
    {
        $this->config = $config['openweathermap'];
        $this->locations = $config['weather_locations'] ?? []; 
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function getWeatherForLocation(string $location): array
    {
        $uri = $this->config['base_uri'];
        $apiKey = $this->config['api_key'];

        $this->httpClient->setUri($uri);
        $this->httpClient->setParameterGet([
            'q'     => $location,
            'appid' => $apiKey,
            'units' => 'metric' // or 'imperial'
        ]);

        try {
            $response = $this->httpClient->send();

            // Handle HTTP Errors (e.g., 404 Not Found, 401 Unauthorized)
            if (!$response->isSuccess()) {
                $errorMessage = "API Error: " . $response->getStatusCode() . " for location: " . $location;
                $this->logger->err($errorMessage);
                
                if ($response->getStatusCode() === 404) {
                    throw new \Exception("Location not found.");
                }
                throw new \Exception("Could not fetch weather data due to an API error.");
            }

            // Decode and process the response
            $data = json_decode($response->getBody(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode API response: " . json_last_error_msg());
            }

            // Return structured data
            return [
                'city'        => $data['name'],
                'temperature' => $data['main']['temp'],
                'description' => $data['weather'][0]['description'],
                'icon'        => $data['weather'][0]['icon'],
            ];
            
        } catch (\Exception $e) {
            // Proper exception handling and logging (Step 4.1 requirement)
            $this->logger->crit("Fatal Weather Service Error: " . $e->getMessage() . " - Location: " . $location);
            throw $e; // Re-throw the exception to be handled by the Controller
        }
    }
    
    // Add the new method to implement the interface
    public function getAvailableLocations(): array
    {
        return $this->locations;
    }
}