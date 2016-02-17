<?php

namespace Actuator\Controller;

use Actuator\Application;
use Actuator\Health\Indicator\CompositeHealthIndicator;
use Symfony\Component\HttpFoundation\Request;

class HealthController
{
    public function __invoke(Application $app, Request $request)
    {
        $healthAggregator = $app['health.aggregator'];
        $healthIndicators = $app['health.indicators'];

        assert(!is_null($healthAggregator), "health.aggregator must not be null");
        assert(!is_null($healthIndicators), "health.indicators must not be null");

        $healthIndicator = new CompositeHealthIndicator($healthAggregator);
        foreach ($healthIndicators as $key => $entry) {
            $healthIndicator->addHealthIndicator($key, $entry);
        }

        return $healthIndicator->health();
    }
}
