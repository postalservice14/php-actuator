<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\Health;

/**
 * Strategy interface used to provide an indication of application health.
 */
interface HealthIndicatorInterface
{
    /**
     * Return an indication of health.
     *
     * @return Health
     */
    public function health();
}
