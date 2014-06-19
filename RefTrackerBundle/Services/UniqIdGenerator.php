<?php

namespace Ars\RefTrackerBundle\Services;

/**
 * Class UniqIdGenerator
 *
 * Simple uniq ID generator (also configured as service)
 *
 * @package Ars\RefTrackerBundle\Services
 */

class UniqIdGenerator
{
    /**
     * Generate uniq ID generator
     *
     * @param int $length
     * @return string
     */
    public function generate($length = 6)
    {
        return substr(hash('sha512', microtime(true)), 0, $length);
    }
}