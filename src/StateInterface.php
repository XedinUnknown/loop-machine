<?php

namespace XedinUnknown\LoopMachine;

use Dhii\Data\ValueAwareInterface;
use Dhii\Util\String\StringableInterface;

/**
 * Something that can represent a machine state.
 *
 * @since [*next-version*]
 */
interface StateInterface extends ValueAwareInterface, StringableInterface
{
    /**
     * Determines whether this state is equivalent to another state.
     *
     * @since [*next-version*]
     *
     * @param ValueAwareInterface|mixed $state A machine state representation.
     *
     * @return bool True if this state is equivalent to the given state; false otherwise.
     */
    public function isEqualTo($state);
}
