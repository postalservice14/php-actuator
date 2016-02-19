<?php

namespace Actuator\Health\Indicator;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcached in-memory data stores.
 *
 * @package Actuator\Health\Indicator
 */
class MemcachedHealthIndicator extends BaseMemcacheHealthIndicator
{
    /**
     * MemcacheHealthIndicator constructor.
     * @param \Memcached $memcached
     */
    public function __construct(\Memcached $memcached)
    {
        $this->memcacheInstance = $memcached;
    }
}
