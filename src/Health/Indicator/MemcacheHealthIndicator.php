<?php

namespace Actuator\Health\Indicator;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcache in-memory data stores.
 */
class MemcacheHealthIndicator extends BaseMemcacheHealthIndicator
{
    /**
     * MemcacheHealthIndicator constructor.
     *
     * @param \Memcache $memcache
     */
    public function __construct(\Memcache $memcache)
    {
        assert(!is_null($memcache), 'Memcache must not be null');

        $this->memcacheInstance = $memcache;
    }
}
