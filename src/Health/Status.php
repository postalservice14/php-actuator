<?php

namespace Actuator\Health;

/**
 * Value object to express state of a component or subsystem.
 *
 * @package Actuator\Health
 */
class Status
{
    /**
     * Value representing unknown state.
     */
    const UNKNOWN = 'UNKNOWN';

    /**
     * Value representing up state.
     */
    const UP = 'UP';

    /**
     * Value representing down state.
     */
    const DOWN = 'DOWN';

    /**
     * Value representing out-of-service state.
     */
    const OUT_OF_SERVICE = 'OUT_OF_SERVICE';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $description;

    /**
     * Create a new Status instance with the given code and description.
     *
     * @param string $code
     * @param string $description
     */
    public function __construct($code, $description = '')
    {
        assert(!is_null($code));

        $this->code = $code;
        $this->description = $description;
    }

    /**
     * Return the code for this status.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return the description of this status.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
