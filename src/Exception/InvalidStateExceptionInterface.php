<?php

namespace XedinUnknown\LoopMachine\Exception;

/**
 * Something that can represent an exception which indicates that a machine state is invalid.
 *
 * @since [*next-version*]
 */
interface InvalidStateExceptionInterface extends ExceptionInterface
{
    /**
     * Retrieves the invalid state.
     *
     * @since [*next-version*]
     *
     * @return mixed The state.
     */
    public function getState();

    /**
     * Retrieves the machine that is associated with the state.
     *
     * @since [*next-version*]
     *
     * @return LoopMachineInterface The machine instance, if any.
     */
    public function getMachine();
}
