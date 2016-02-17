<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * Memcache in-memory data stores.
 *
 * @package Actuator\Health\Indicator
 */
class MemcacheHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * MemcacheHealthIndicator constructor.
     * @param \Memcache $memcache
     */
    public function __construct(\Memcache $memcache)
    {
        assert(!is_null($memcache), 'Memcache must not be null');

        $this->memcache = $memcache;
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
            $version = $this->memcache->getversion();
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
