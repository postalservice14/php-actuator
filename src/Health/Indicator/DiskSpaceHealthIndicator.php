<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;

/**
 * A HealthIndicator that checks available disk space and reports a status of
 * Status::DOWN when it drops below a configurable threshold.
 */
class DiskSpaceHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var DiskSpaceHealthIndicatorProperties
     */
    private $properties;

    /**
     * Create a new DiskSpaceHealthIndicator.
     *
     * @param DiskSpaceHealthIndicatorProperties $properties
     */
    public function __construct(DiskSpaceHealthIndicatorProperties $properties = null)
    {
        if (is_null($properties)) {
            $properties = new DiskSpaceHealthIndicatorProperties();
        }

        $this->properties = $properties;
    }

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
        $directoryPath = $this->properties->getDirectory();
        $diskFreeInBytes = disk_free_space($directoryPath);
        if ($diskFreeInBytes >= $this->properties->getThreshold()) {
            $builder->up();
        } else {
            $builder->down();
        }
        $builder->withDetail('total', disk_total_space($directoryPath));
        $builder->withDetail('free', $diskFreeInBytes);
        $builder->withDetail('threshold', $this->properties->getThreshold());
    }
}
