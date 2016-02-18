<?php

/**
 * Override built-in PHP functions in Actuator\Health\Indicator namespace.
 */
namespace Actuator\Health\Indicator {

    use Actuator\Test\Health\Indicator\DiskSpaceHealthIndicatorTest;

    /**
     * Mock built-in PHP function.
     *
     * @param string $directory
     * @return int
     */
    function disk_free_space($directory) {
        return DiskSpaceHealthIndicatorTest::$mockDiskFreeSpace;
    }

    /**
     * Mock built-in PHP function.
     *
     * @return int
     */
    function disk_total_space() {
        return DiskSpaceHealthIndicatorTest::$mockDiskTotalSpace;
    }
}

namespace Actuator\Test\Health\Indicator {

    use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
    use Actuator\Health\Indicator\DiskSpaceHealthIndicatorProperties;
    use Actuator\Health\Status;

    class DiskSpaceHealthIndicatorTest extends \PHPUnit_Framework_TestCase
    {
        const THRESHOLD_BYTES = 1024;

        /**
         * Value to use for mock disk_free_space function.
         *
         * @var int
         */
        public static $mockDiskFreeSpace;

        /**
         * Value to use for mock disk_total_space function.
         *
         * @var int
         */
        public static $mockDiskTotalSpace;

        /**
         * @var DiskSpaceHealthIndicator
         */
        private $healthIndicator;

        public function setUp()
        {
            $this->healthIndicator = new DiskSpaceHealthIndicator(
                $this->createProperties(__DIR__, static::THRESHOLD_BYTES)
            );
        }

        /** @test */
        public function diskSpaceIsUpWithoutProperties()
        {
            static::$mockDiskFreeSpace = static::THRESHOLD_BYTES + 10;
            static::$mockDiskTotalSpace = static::THRESHOLD_BYTES + 10;
            $healthIndicator = new DiskSpaceHealthIndicator();

            $health = $healthIndicator->health();
            $this->assertEquals(Status::DOWN, $health->getStatus()->getCode());
            $details = $health->getDetails();
            $this->assertNotEquals(static::THRESHOLD_BYTES, $details['threshold']);
            $this->assertEquals(static::THRESHOLD_BYTES + 10, $details['free']);
            $this->assertEquals(static::THRESHOLD_BYTES + 10, $details['total']);
        }

        /** @test */
        public function diskSpaceIsUp()
        {
            static::$mockDiskFreeSpace = static::THRESHOLD_BYTES + 10;
            static::$mockDiskTotalSpace = static::THRESHOLD_BYTES + 10;

            $health = $this->healthIndicator->health();
            $this->assertEquals(Status::UP, $health->getStatus()->getCode());
            $details = $health->getDetails();
            $this->assertEquals(static::THRESHOLD_BYTES, $details['threshold']);
            $this->assertEquals(static::THRESHOLD_BYTES + 10, $details['free']);
            $this->assertEquals(static::THRESHOLD_BYTES + 10, $details['total']);
        }

        /** @test */
        public function diskSpaceIsDown()
        {
            static::$mockDiskFreeSpace = static::THRESHOLD_BYTES - 10;
            static::$mockDiskTotalSpace = static::THRESHOLD_BYTES + 10;

            $health = $this->healthIndicator->health();
            $this->assertEquals(Status::DOWN, $health->getStatus()->getCode());
            $details = $health->getDetails();
            $this->assertEquals(static::THRESHOLD_BYTES, $details['threshold']);
            $this->assertEquals(static::THRESHOLD_BYTES - 10, $details['free']);
            $this->assertEquals(static::THRESHOLD_BYTES + 10, $details['total']);
        }

        private function createProperties($directory, $threshold)
        {
            $properties = new DiskSpaceHealthIndicatorProperties();
            $properties->setDirectory($directory);
            $properties->setThreshold($threshold);

            return $properties;
        }
    }
}
