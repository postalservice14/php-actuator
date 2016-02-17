<?php

namespace Actuator;

use Actuator\Controller\HealthController;
use Actuator\Provider\HealthControllerProvider;
use Actuator\Provider\HealthServiceProvider;
use Silex\Application as SilexApplication;

class Application extends SilexApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->register(new HealthServiceProvider());
    }
}
