<?php
namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Health;
use Actuator\Health\HealthBuilder;
use Actuator\Health\Indicator\CompositeHealthIndicator;
use Actuator\Health\Indicator\HealthIndicatorInterface;
use Actuator\Health\OrderedHealthAggregator;

class CompositeHealthIndicatorTest extends \PHPUnit_Framework_TestCase
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

    public function testCreateWithIndicators()
    {
        $indicators = [
            'one' => $this->one,
            'two' => $this->two
        ];

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $details = $compositeIndicator->health()
            ->getDetails();
        $this->assertCount(2, (array)$details);
        $this->assertEquals($this->createHealth(1), $details->one);
        $this->assertEquals($this->createHealth(2), $details->two);
    }


    /**
     * @param mixed $detail detail key and value
     * @return Health
     */
    private function createHealth($detail)
    {
        return (new HealthBuilder())->unknown()
            ->withDetail($detail, $detail)
            ->build();
    }

    public function testCreateWithIndicatorsAndAdd()
    {
        $indicators = [
            'one' => $this->one,
            'two' => $this->two
        ];

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator->addHealthIndicator('three', $this->three);
        $details = $compositeIndicator->health()
            ->getDetails();

        $this->assertCount(3, (array)$details);
        $this->assertEquals($this->createHealth(1), $details->one);
        $this->assertEquals($this->createHealth(2), $details->two);
        $this->assertEquals($this->createHealth(3), $details->three);
    }

    public function testCreateWithoutAndAdd()
    {
        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);

        $compositeIndicator->addHealthIndicator('one', $this->one);
        $compositeIndicator->addHealthIndicator('two', $this->two);

        $details = $compositeIndicator->health()
            ->getDetails();

        $this->assertCount(2, (array)$details);
        $this->assertEquals($this->createHealth(1), $details->one);
        $this->assertEquals($this->createHealth(2), $details->two);
    }

    public function testSerialization()
    {
        $indicators = [
            'db1' => $this->one,
            'db2' => $this->two
        ];
        $innerComposite = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);
        $compositeIndicator->addHealthIndicator('db', $innerComposite);


        $health = $compositeIndicator->health();

        $expected = json_encode([
            'status' => 'UNKNOWN',
            'db' => [
                'status' => 'UNKNOWN',
                'db1' => [
                    'status' => 'UNKNOWN',
                    '1' => '1'
                ],
                'db2' => [
                    'status' => 'UNKNOWN',
                    '2' => '2'
                ]
            ]

        ]);
        $this->assertEquals($expected, json_encode($health));
    }

    public function testSerializationOfIndicator()
    {
        $indicators = [
            'db1' => $this->one,
            'db2' => $this->two
        ];
        $innerComposite = new CompositeHealthIndicator($this->healthAggregator, $indicators);

        $compositeIndicator = new CompositeHealthIndicator($this->healthAggregator);
        $compositeIndicator->addHealthIndicator('db', $innerComposite);


        $health = $compositeIndicator->health();

        $expected = json_encode([
            'status' => 'UNKNOWN',
            'db' => [
                'status' => 'UNKNOWN',
                'db1' => [
                    'status' => 'UNKNOWN',
                    '1' => '1'
                ],
                'db2' => [
                    'status' => 'UNKNOWN',
                    '2' => '2'
                ]
            ]

        ]);
        $this->assertEquals($expected, json_encode($compositeIndicator));
    }

        protected function setUp()
    {
        $this->healthAggregator = new OrderedHealthAggregator();
        $this->healthIndicator = new CompositeHealthIndicator($this->healthAggregator);

        $this->one = $this->createMockHealthIndicator('1');
        $this->two = $this->createMockHealthIndicator('2');
        $this->three = $this->createMockHealthIndicator('3');
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


}
