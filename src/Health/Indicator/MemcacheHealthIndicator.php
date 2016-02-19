<?php

namespace Actuator\Health\Indicator;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcache in-memory data stores.
 *
 * @package Actuator\Health\Indicator
 */
class MemcacheHealthIndicator extends BaseMemcacheHealthIndicator
{
    /**
     * MemcacheHealthIndicator constructor.
     * @param \Memcache $memcache
     */
    public function __construct(\Memcache $memcache)
    {
        $this->memcacheInstance = $memcache;
    }
}
