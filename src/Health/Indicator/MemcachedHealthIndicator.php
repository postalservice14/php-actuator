<?php

namespace Actuator\Health\Indicator;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcached in-memory data stores.
 */
class MemcachedHealthIndicator extends BaseMemcacheHealthIndicator
{
    /**
     * MemcacheHealthIndicator constructor.
     *
     * @param \Memcached $memcached
     */
    public function __construct(\Memcached $memcached)
    {
        assert(!is_null($memcached), 'Memcached must not be null');

        $this->memcacheInstance = $memcached;
    }
}
