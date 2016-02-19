<?php

namespace Actuator\Health;

/**
 * Default HealthAggregator implementation that aggregates Health
 * instances and determines the final system state based on a simple ordered list.
 *
 * @package Actuator\Health
 */
class OrderedHealthAggregator extends AbstractHealthAggregator
{
    /**
     * @var string[]
     */
    private $statusOrder;

    /**
     * Create a new OrderedHealthAggregator instance.
     */
    public function __construct()
    {
        $this->setStatusOrder(
            array(Status::DOWN, Status::OUT_OF_SERVICE, Status::UP, Status::UNKNOWN)
        );
    }

    /**
     * @param Status[] $candidates
     * @return Status
     */
    protected function aggregateStatus($candidates)
    {
        $filteredCandidates = array();
        foreach ($candidates as $candidate) {
            if (array_search($candidate->getCode(), $this->statusOrder) !== false) {
                $filteredCandidates[] = $candidate;
            }
        }

        if (count($filteredCandidates) === 0) {
            return new Status(Status::UNKNOWN);
        }

        usort($filteredCandidates, array($this, 'statusComparator'));

        return $filteredCandidates[0];
    }

    /**
     * Callable used to order Status.
     *
     * @param Status $s1
     * @param Status $s2
     * @return int
     */
    private function statusComparator($s1, $s2)
    {
        if (array_search($s1->getCode(), $this->statusOrder) === array_search($s2->getCode(), $this->statusOrder)) {
            return 0;
        }

        return (array_search($s1->getCode(), $this->statusOrder)
            < array_search($s2->getCode(), $this->statusOrder)) ? -1 : 1;
    }

    /**
     * Set the ordering of the status.
     *
     * @param array $statusOrder an ordered array of the status
     */
    public function setStatusOrder($statusOrder)
    {
        assert(!is_null($statusOrder));
        $this->statusOrder = $statusOrder;
    }
}
