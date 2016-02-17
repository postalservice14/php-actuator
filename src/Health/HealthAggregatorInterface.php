<?php

namespace Actuator\Health;

/**
 * Strategy interface used by CompositeHealthIndicator to aggregate Health
 * instances into a final one
 *
 * @package Actuator\Health
 */
interface HealthAggregatorInterface
{
    /**
     * Aggregate several given Health instances into one.
     *
     * @param Health[] $healths
     * @return Health
     */
    public function aggregate($healths);
}
