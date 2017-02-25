<?php

namespace XedinUnknown\LoopMachine\Exception;

use XedinUnknown\LoopMachine\LoopMachineInterface;

/**
 * Common functionality for invalid machine state exceptions.
 *
 * @since [*next-version*]
 */
abstract class AbstractInvalidStateException extends AbstractException
{
    /**
     * The state represented by this instance.
     *
     * @since [*next-version*]
     *
     * @var mixed
     */
    protected $state;

    /**
     * The machine associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var LoopMachineInterface
     */
    protected $machine;

    /**
     * Assigns a machine state to this instance.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The state to set.
     *
     * @return AbstractInvalidStateException This instance.
     */
    protected function _setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Retrieves the machine state of this instance.
     *
     * @since [*next-version*]
     *
     * @return mixed The state.
     */
    protected function _getState()
    {
        return $this->state;
    }

    /**
     * Associates a machine with this instance.
     *
     * @since [*next-version*]
     *
     * @param LoopMachineInterface $machine The machine to set.
     *
     * @return AbstractInvalidStateException This instance.
     */
    protected function _setMachine(LoopMachineInterface $machine)
    {
        $this->machine = $machine;

        return $this;
    }

    /**
     * Retrieves the machine associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return LoopMachineInterface The machine instance.
     */
    protected function _getMachine()
    {
        return $this->machine;
    }
}
