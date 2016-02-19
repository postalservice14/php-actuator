<?php
namespace Actuator\test\Health\Indicator;

use Actuator\Health\Health;
use Actuator\Health\HealthBuilder;
use Actuator\Health\Indicator\CompositeHealthIndicator;
use Actuator\Health\Indicator\HealthIndicatorInterface;
use Actuator\Health\OrderedHealthAggregator;

class CompositeHealthIndicatorTest extends \PHPUnit_Framework_testCase
{
    /**
     * @var CompositeHealthIndicator
     */
    private $healthIndicator;

    /**
     * @var OrderedHealthAggregator
     */
    private $healthAggregator;

    /**
     * @var HealthIndicatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $one;

    /**
     * @var HealthIndicatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $two;

    /**
     * @var HealthIndicatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $three;

    protected function setUp()
    {
        $this->healthAggregator = new OrderedHealthAggregator();
        $this->healthIndicator = new CompositeHealthIndicator($this->healthAggregator);

        $this->one = $this->createMockHealthIndicator('one');
        $this->two = $this->createMockHealthIndicator('two');
        $this->three = $this->createMockHealthIndicator('three');
    }

    /**
     * @param mixed $detail detail key and value
     * @return HealthIndicatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockHealthIndicator($detail)
    {
        $mock = $this->getMock('\Actuator\Health\Indicator\HealthIndicatorInterface');
        $mock->expects($this->any())
            ->method('health')
            ->willReturn($this->createHealth($detail));

        return $mock;
    }

    /**
     * @param string $detail detail key and value
     * @return Health
     */
    private function createHealth($detail)
    {
        $healthBuilder = new HealthBuilder();
        return $healthBuilder->unknown()
            ->withDetail($detail, $detail)
            ->build();
    }

    /**
     * @test
     */
    public function createWithIndicators()
    {
        $indicators = array(
            'one' => $this->one,
            'two' => $this->two
        );

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $details = $compositeIndicator->health()
            ->getDetails();
        $this->assertCount(2, (array)$details);
        $this->assertEquals($this->createHealth('one'), $details['one']);
        $this->assertEquals($this->createHealth('two'), $details['two']);
    }

    /**
     * @test
     */
    public function createWithIndicatorsAndAdd()
    {
        $indicators = array(
            'one' => $this->one,
            'two' => $this->two
        );

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator->addHealthIndicator('three', $this->three);
        $details = $compositeIndicator->health()
            ->getDetails();

        $this->assertCount(3, (array)$details);
        $this->assertEquals($this->createHealth('one'), $details['one']);
        $this->assertEquals($this->createHealth('two'), $details['two']);
        $this->assertEquals($this->createHealth('three'), $details['three']);
    }

    /**
     * @test
     */
    public function createWithoutAndAdd()
    {
        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);

        $compositeIndicator->addHealthIndicator('one', $this->one);
        $compositeIndicator->addHealthIndicator('two', $this->two);

        $details = $compositeIndicator->health()
            ->getDetails();

        $this->assertCount(2, $details);
        $this->assertEquals($this->createHealth('one'), $details['one']);
        $this->assertEquals($this->createHealth('two'), $details['two']);
    }

    /**
     * @test
     */
    public function serialization()
    {
        $indicators = array(
            'db1' => $this->one,
            'db2' => $this->two
        );
        $innerComposite = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);
        $compositeIndicator->addHealthIndicator('db', $innerComposite);


        $health = $compositeIndicator->health();

        $expected = json_encode(array(
            'status' => 'UNKNOWN',
            'db' => array(
                'status' => 'UNKNOWN',
                'db1' => array(
                    'status' => 'UNKNOWN',
                    'one' => 'one'
                ),
                'db2' => array(
                    'status' => 'UNKNOWN',
                    'two' => 'two'
                )
            )
        ));
        $this->assertEquals($expected, json_encode($health));
    }

    /**
     * @test
     */
    public function serializationOfIndicator()
    {
        $indicators = array(
            'db1' => $this->one,
            'db2' => $this->two
        );
        $innerComposite = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);
        $compositeIndicator->addHealthIndicator('db', $innerComposite);

        $health = $compositeIndicator->health();

        $expected = json_encode(array(
            'status' => 'UNKNOWN',
            'db' => array(
                'status' => 'UNKNOWN',
                'db1' => array(
                    'status' => 'UNKNOWN',
                    'one' => 'one'
                ),
                'db2' => array(
                    'status' => 'UNKNOWN',
                    'two' => 'two'
                )
            )
        ));
        $this->assertEquals($expected, json_encode($health));
    }
}
