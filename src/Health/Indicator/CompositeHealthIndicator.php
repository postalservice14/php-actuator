<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\Health;
use Actuator\Health\HealthAggregatorInterface;
use JsonSerializable;

/**
 * HealthIndicator that returns health indications from all registered delegates.
 *
 * @package Actuator\Health\Indicator
 */
class CompositeHealthIndicator implements HealthIndicatorInterface, JsonSerializable
{
    /**
     * @var HealthIndicatorInterface[]
     */
    private $indicators;

    /**
     * @var HealthAggregatorInterface
     */
    private $healthAggregator;

    /**
     * Create a new CompositeHealthIndicator from the specified indicators.
     *
     * @param HealthAggregatorInterface $healthAggregator
     * @param HealthIndicatorInterface[] $indicators
     */
    public function __construct(HealthAggregatorInterface $healthAggregator, array $indicators = array())
    {
        assert(!is_null($healthAggregator), 'HealthAggregator must not be null');

        $this->indicators = $indicators;
        $this->healthAggregator = $healthAggregator;
    }

    /**
     * @param string $name
     * @param HealthIndicatorInterface $indicator
     */
    public function addHealthIndicator($name, HealthIndicatorInterface $indicator)
    {
        $this->indicators[$name] = $indicator;
    }

    /**
     * Return an indication of health.
     *
     * @return Health
     */
    public function health()
    {
        $healths = array();
        foreach ($this->indicators as $key => $indicator) {
            $healths[$key] = $indicator->health();
        }

        return $this->healthAggregator->aggregate($healths);
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
        return $this->health();
    }
}
