<?php

namespace Actuator\Health;

/**
 * Base HealthAggregator implementation to allow subclasses to focus on
 * aggregating the Status instances and not deal with contextual details etc.
 */
abstract class AbstractHealthAggregator implements HealthAggregatorInterface
{
    /**
     * Aggregate several given Health instances into one.
     *
     * @param Health[] $healths
     *
     * @return Health
     */
    public function aggregate($healths)
    {
        $statusCandidates = [];
        foreach ($healths as $key => $health) {
            $statusCandidates[] = $health->getStatus();
        }
        $status = $this->aggregateStatus($statusCandidates);
        $details = $this->aggregateDetails($healths);
        $builder = new HealthBuilder($status, $details);

        return $builder->build();
    }

    /**
     * @param Status[] $candidates
     *
     * @return Status
     */
    abstract protected function aggregateStatus($candidates);

    /**
     * Return the map of 'aggregate' details that should be used from the specified
     * healths.
     *
     * @param Health[] $healths
     *
     * @return array
     */
    protected function aggregateDetails($healths)
    {
        return $healths;
    }
}
