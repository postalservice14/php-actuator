<?php

namespace Actuator\Test\Health;

use Actuator\Health\Status;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function constructorWithDescription()
    {
        $status = new Status(Status::UNKNOWN, 'Unknown Description');

        $this->assertEquals(Status::UNKNOWN, $status->getCode());
        $this->assertEquals('Unknown Description', $status->getDescription());
    }
}
