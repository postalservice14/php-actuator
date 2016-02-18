<?php

namespace Actuator\Health;

/**
 * Carries information about the health of a component or subsystem.
 *
 * @package Actuator\Health
 */
final class Health implements \JsonSerializable
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

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $merged = array_merge(['status' => $this->status->getCode()], $this->getDetails());
        return $merged;
    }
}
