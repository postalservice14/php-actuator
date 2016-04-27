<?php

namespace Actuator\Health\Indicator;

/**
 * External configuration properties for DiskSpaceHealthIndicator.
 */
class DiskSpaceHealthIndicatorProperties
{
    /**
     * One MB in bytes.
     */
    const MEGABYTES = 1048576;

    /**
     * Default threshold - 10MB.
     */
    const DEFAULT_THRESHOLD = 10485760;

    /**
     * Path used to compute the available disk space.
     *
     * @var string
     */
    private $directory = '.';

    /**
     * Minimum disk space that should be available, in bytes.
     *
     * @var int
     */
    private $threshold = self::DEFAULT_THRESHOLD;

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        assert(is_dir($directory), 'Path '.$directory.' is not a directory');
        assert(is_readable($directory), 'Path '.$directory.' cannot be read');
        $this->directory = $directory;
    }

    /**
     * @return int
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * @param int $threshold
     */
    public function setThreshold($threshold)
    {
        assert(intval($threshold) > 0, 'Threshold must be greater than 0');
        $this->threshold = $threshold;
    }
}
