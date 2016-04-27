<?php

namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Simple implementation of a HealthIndicator returning status information for
 * a Doctrine Connection.
 */
class DoctrineConnectionHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * MemcacheHealthIndicator constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        assert(!is_null($connection), 'Connection must not be null');

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
            $this->connection->query('SELECT 1=1');
        } catch (DBALException $e) {
            $builder->down($e);

            return;
        }

        $builder->up();
    }
}
