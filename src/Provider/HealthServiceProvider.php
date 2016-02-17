<?php

namespace Actuator\Provider;

use Actuator\Controller\HealthController;
use Actuator\Health\Health;
use Actuator\Health\OrderedHealthAggregator;
use Silex\Application;
use Silex\ServiceProviderInterface;

class HealthServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app->view(function (Health $healthResult) use ($app) {
            $healthDetails = array();
            foreach ($healthResult->getDetails() as $key => $healthDetail) {
                $healthDetails[$key] = array_merge(
                    array('status' => $healthDetail->getStatus()->getCode()),
                    $healthDetail->getDetails()
                );
            }
            $healthDetails = array_merge(
                array('status' => $healthResult->getStatus()->getCode()),
                $healthDetails
            );

            return $app->json($healthDetails);
        });

        $app['health.aggregator'] = $app->share(function () {
            return new OrderedHealthAggregator();
        });
        $app['health.indicators'] = array();
        $app['health.endpoint'] = '/health';
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->get($app['health.endpoint'], new HealthController());
    }
}
