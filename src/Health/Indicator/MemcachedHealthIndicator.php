<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcached in-memory data stores.
 *
 * @package Actuator\Health\Indicator
 */
class MemcachedHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * MemcacheHealthIndicator constructor.
     * @param \Memcached $memcached
     */
    public function __construct(\Memcached $memcached)
    {
        assert(!is_null($memcached), 'Memcached must not be null');

        $this->memcached = $memcached;
    }

    /**
     * Actual health check logic.
     *
     * @param HealthBuilder $builder
     * @throws \Exception any Exception that should create a Status::DOWN
     * system status.
     */
    protected function doHealthCheck(HealthBuilder $builder)
    {
        try {
            $version = $this->memcached->getversion();
        } catch (\Exception $e) {
            $builder->down($e);
            return;
        }

        if (!$version) {
            $builder->down();
            return;
        }

        $builder->up()->withDetail('version', $version);
    }
}
