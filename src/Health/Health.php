<?php

namespace Actuator\Health;

/**
 * Carries information about the health of a component or subsystem.
 *
 * @package Actuator\Health
 */
final class Health
{
    /**
     * @var Status
     */
    private $status;

    /**
     * @var array
     */
    private $details;

    /**
     * Create a new Health instance with the specified status and details from builder.
     *
     * @param HealthBuilder $builder
     */
    public function __construct(HealthBuilder $builder)
    {
        $this->status = $builder->status;
        $this->details = $builder->details;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}
