<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;
use PhpAmqpLib\Connection\AbstractConnection;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * an AMQP Connection.
 */
class AMQPConnectionHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var AbstractConnection
     */
    private $connection;

    /**
     * MemcacheHealthIndicator constructor.
     *
     * @param AbstractConnection $connection
     */
    public function __construct(AbstractConnection $connection)
    {
        $this->connection = $connection;
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
        try {
            $this->connection->reconnect();
            $serverProperties = $this->connection->getServerProperties();
            $builder->withDetail('version', $serverProperties['version'][1]);
        } catch (\Exception $e) {
            $builder->down($e);

            return;
        }

        $builder->up();
    }
}
