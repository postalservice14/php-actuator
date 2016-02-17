<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;

/**
 * Base HealthIndicator implementations that encapsulates creation of
 * Health instance and error handling.
 *
 * @package Actuator\Health\Indicator
 */
abstract class AbstractHealthIndicator implements HealthIndicatorInterface
{
    public function health()
    {
        $builder = new HealthBuilder();
        try {
            $this->doHealthCheck($builder);
        } catch (\Exception $e) {
            $builder->down($e);
        }

        return $builder->build();
    }

    /**
     * Actual health check logic.
     *
     * @param HealthBuilder $builder
     * @throws \Exception any Exception that should create a Status::DOWN
     * system status.
     */
    abstract protected function doHealthCheck(HealthBuilder $builder);
}
