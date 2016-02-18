<?php

namespace Actuator\Health;

/**
 * Builder for creating imutable Health instances.
 *
 * @package Actuator\Health
 */
class HealthBuilder
{
    /**
     * @var Status
     */
    public $status;

    /**
     * @var array
     */
    public $details;

    /**
     * Create new Builder instance.
     *
     * @param Status $status
     * @param array $details
     */
    public function __construct(Status $status = null, $details = array())
    {
        if (is_null($status)) {
            $status = new Status(Status::UNKNOWN);
        }

        $this->status = $status;
        $this->details = (object)$details;
    }

    /**
     * Set status to Status::DOWN and add details for given Exception if available.
     *
     * @param \Exception $exception The exception
     * @return HealthBuilder
     */
    public function down(\Exception $exception = null)
    {
        $builder = $this->status(new Status(Status::DOWN));

        if (!is_null($exception)) {
            $builder->withException($exception);
        }

        return $builder;
    }

    /**
     * Create a new Builder instance with a Status::UNKNOWN status.
     *
     * @return HealthBuilder
     */
    public function unknown()
    {
        return $this->status(new Status(Status::UNKNOWN));
    }

    /**
     * Create a new Builder instance with a Status::UP status.
     *
     * @return HealthBuilder
     */
    public function up()
    {
        return $this->status(new Status(Status::UP));
    }

    /**
     * Create a new Builder instance with a Status::OUT_OF_SERVICE status.
     *
     * @return HealthBuilder
     */
    public function outOfService()
    {
        return $this->status(Status::OUT_OF_SERVICE);
    }

    /**
     * Set status to given Status instance or status code.
     *
     * @param Status|string $status
     * @return $this
     */
    public function status($status)
    {
        if (!($status instanceof Status)) {
            $status = new Status($status);
        }

        $this->status = $status;
        return $this;
    }

    /**
     * Record detail for given {@link Exception}.
     *
     * @param \Exception $exception
     * @return $this
     */
    public function withException(\Exception $exception)
    {
        assert(!is_null($exception), 'Exception must not be null');
        return $this->withDetail('error', $exception->getMessage());
    }

    /**
     * Record detail using key and message.
     *
     * @param string $key the detail key
     * @param string $message the detail message
     * @return $this
     */
    public function withDetail($key, $message)
    {
        assert(!is_null($key), 'Key must not be null');
        assert(!is_null($message), 'Message must not be null');
        $this->details->{$key} = $message;
        return $this;
    }

    /**
     * @return Health
     */
    public function build()
    {
        return new Health($this);
    }
}
