<?php

namespace Vendor\CookBundle;

use Vendor\MachineBundle\Electrical;
use ArrayAccess;
use Vendor\MachineBundle\Timer;

/**
 * Takes a donnut and returns it toasted
 */
class Toaster extends Electrical implements ToasterInterface, ArrayAccess
{

    /**
     * Toasting duration in seconds
     *
     * @var int
     */
    private $duration = 20;

    /**
     * Timer used to measure the toasting duration
     *
     * @var Timer
     */
    protected $timer = null;

    /**
     * Instanciates a new Toaster
     *
     * @param int duration
     * @param Timer timer
     */
    public function __construct($duration, Timer $timer)
    {
        $this->duration = $duration;
        $this->timer = $timer;
    }

    /**
     * Gets: Toasting duration in seconds
     *
     * @return int duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets: Toasting duration in seconds
     *
     * @param int duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


}
