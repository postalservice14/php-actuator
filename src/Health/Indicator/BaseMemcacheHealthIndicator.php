<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;

/**
 * Base class for memcache HealthIndicator.
 */
class BaseMemcacheHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var \Memcached|\Memcache
     */
    protected $memcacheInstance;

    /**
     * Actual health check logic.
     *
     * @param HealthBuilder $builder
     *
     * @throws \Exception any Exception that should create a Status::DOWN
     *                    system status.
     */
    protected function doHealthCheck(HealthBuilder $builder)
    {
        try {
            $version = $this->memcacheInstance->getversion();
        } catch (\Exception $e) {
            $builder->down($e);

            return;
        }

        if ((is_bool($version) && $version === false) ||
            ((is_array($version) && count($version) === 0))
        ) {
            $builder->down();

            return;
        }

        $builder->up()->withDetail('version', $version);
    }
}
