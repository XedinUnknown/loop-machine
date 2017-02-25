<?php

namespace XedinUnknown\LoopMachine;

use Dhii\Machine\FiniteStateMachineInterface;
use Iterator;
use SplSubject;

/**
 * Something that provides an event-less interface into a loop's events.
 *
 * @since [*next-version*]
 */
interface LoopMachineInterface extends
    FiniteStateMachineInterface,
    SplSubject
{
    /**
     * Indicates that the machine is about to begin processing.
     *
     * @since [*next-version*]
     */
    const STATE_START = 1;

    /**
     * Indicates that the machine is currently processing an item.
     *
     * @since [*next-version*]
     */
    const STATE_LOOP = 2;

    /**
     * Indicates that the machine has finished processing.
     *
     * @since [*next-version*]
     */
    const STATE_END = 0;

    /**
     * Retrieves the current state of the machine.
     *
     * @since [*next-version*]
     * @see STATE_START
     * @see STATE_LOOP
     * @see STATE_END
     *
     * @return StateInterface The representation of the current state.
     */
    public function getCurrentState();

    /**
     * Retrieves the item that is currently being processed.
     *
     * @since [*next-version*]
     *
     * @return mixed The current item.
     */
    public function getCurrentItem();

    /**
     * Processes a given iterable.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $iterable The iterable.
     */
    public function process($iterable);
}
