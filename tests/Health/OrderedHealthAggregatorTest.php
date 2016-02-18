<?php

namespace Actuator\Test\Health;

use Actuator\Health\HealthBuilder;
use Actuator\Health\OrderedHealthAggregator;
use Actuator\Health\Status;

class OrderedHealthAggregatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderedHealthAggregator
     */
    private $healthAggregator;

    public function setUp()
    {
        $this->healthAggregator = new OrderedHealthAggregator();
    }

    /** @test */
    public function defaultOrder()
    {
        $builder = new HealthBuilder();

        $healths = array();
        $healths['h1'] = $builder->status(new Status(Status::DOWN))->build();
        $healths['h2'] = $builder->status(new Status(Status::UP))->build();
        $healths['h3'] = $builder->status(new Status(Status::UNKNOWN))->build();
        $healths['h4'] = $builder->status(new Status(Status::OUT_OF_SERVICE))->build();

        $this->assertEquals(
            new Status(Status::DOWN),
            $this->healthAggregator->aggregate($healths)->getStatus()
        );
    }

    /** @test */
    public function customOrder()
    {
        $this->healthAggregator->setStatusOrder(
            array(Status::UNKNOWN, Status::UP, Status::OUT_OF_SERVICE, Status::DOWN)
        );

        $builder = new HealthBuilder();

        $healths = array();
        $healths['h1'] = $builder->status(new Status(Status::DOWN))->build();
        $healths['h2'] = $builder->status(new Status(Status::UP))->build();
        $healths['h3'] = $builder->status(new Status(Status::UNKNOWN))->build();
        $healths['h4'] = $builder->status(new Status(Status::OUT_OF_SERVICE))->build();

        $this->assertEquals(
            new Status(Status::UNKNOWN),
            $this->healthAggregator->aggregate($healths)->getStatus()
        );
    }

    /** @test */
    public function defaultOrderWithCustomStatus()
    {
        $builder = new HealthBuilder();

        $healths = array();
        $healths['h1'] = $builder->status(new Status(Status::DOWN))->build();
        $healths['h2'] = $builder->status(new Status(Status::UP))->build();
        $healths['h3'] = $builder->status(new Status(Status::UNKNOWN))->build();
        $healths['h4'] = $builder->status(new Status(Status::OUT_OF_SERVICE))->build();
        $healths['h5'] = $builder->status(new Status('CUSTOM'))->build();

        $this->assertEquals(
            new Status(Status::DOWN),
            $this->healthAggregator->aggregate($healths)->getStatus()
        );
    }

    /** @test */
    public function customOrderWithCustomStatus()
    {
        $this->healthAggregator->setStatusOrder(
            array(Status::DOWN, Status::OUT_OF_SERVICE, Status::UP, Status::UNKNOWN, 'CUSTOM')
        );

        $builder = new HealthBuilder();

        $healths = array();
        $healths['h1'] = $builder->status(new Status(Status::DOWN))->build();
        $healths['h2'] = $builder->status(new Status(Status::UP))->build();
        $healths['h3'] = $builder->status(new Status(Status::UNKNOWN))->build();
        $healths['h4'] = $builder->status(new Status(Status::OUT_OF_SERVICE))->build();
        $healths['h5'] = $builder->status(new Status('CUSTOM'))->build();

        $this->assertEquals(
            new Status(Status::DOWN),
            $this->healthAggregator->aggregate($healths)->getStatus()
        );
    }

    /** @test */
    public function noFilteredStatusResultsInUnknown()
    {
        $this->healthAggregator->setStatusOrder(
            array('FOO')
        );

        $builder = new HealthBuilder();

        $healths = array();
        $healths['h1'] = $builder->status(new Status(Status::DOWN))->build();
        $healths['h2'] = $builder->status(new Status(Status::UP))->build();
        $healths['h3'] = $builder->status(new Status(Status::UNKNOWN))->build();
        $healths['h4'] = $builder->status(new Status(Status::OUT_OF_SERVICE))->build();
        $healths['h5'] = $builder->status(new Status('CUSTOM'))->build();

        $this->assertEquals(
            new Status(Status::UNKNOWN),
            $this->healthAggregator->aggregate($healths)->getStatus()
        );
    }
}
