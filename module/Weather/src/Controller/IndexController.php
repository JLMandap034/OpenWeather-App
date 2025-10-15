<?php
// module/Weather/src/Controller/IndexController.php

namespace Weather\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Paginator\Paginator; // You may need to install this package
use Laminas\Paginator\Adapter\ArrayAdapter;
use Weather\Service\WeatherServiceInterface;

class IndexController extends AbstractActionController
{
    private $weatherService;

    public function __construct(WeatherServiceInterface $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function indexAction()
    {
        $weatherData = null;
        $location = $this->params()->fromQuery('location', null);
        $page = $this->params()->fromQuery('page', 1);

        // 1. Fetch ALL available locations
        $allLocations = $this->weatherService->getAvailableLocations();

        // 2. Set up Paginator
        $paginator = new Paginator(new ArrayAdapter($allLocations));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5); // Show 5 cities per page

        // 3. Fetch weather data if a location was selected
        if ($location) {
            try {
                $weatherData = $this->weatherService->getWeatherForLocation($location);
            } catch (\Exception $e) {
                // Flash messenger for user-friendly errors
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return new ViewModel([
            'weatherData' => $weatherData,
            'paginator'   => $paginator, // Pass the paginated list to the view
            'location'    => $location,
        ]);
    }
}