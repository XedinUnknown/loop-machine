<?php

namespace XedinUnknown\LoopMachine;

use XedinUnknown\LoopMachine\Exception\Exception;
use XedinUnknown\LoopMachine\Exception\InvalidStateException;

/**
 * Represents a machine state.
 *
 * @since [*next-version*]
 */
class State extends AbstractState implements StateInterface
{
    /**
     * @since [*next-version*]
     *
     * @param int|string|bool|float $value The value of this state.
     */
    public function __construct($value)
    {
        $this->_setValue($value);

        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return (string) $this->_getValue();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getValue()
    {
        return $this->_getValue();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return Exception The new exception.
     */
    protected function _createException($message, \Exception $previous = null)
    {
        return new Exception($message, 0, $previous);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return InvalidStateException The new exception.
     */
    protected function _createInvalidStateException($message, $state, LoopMachineInterface $machine = null, \Exception $previous = null)
    {
        return new InvalidStateException($message, 0, $previous, $state, $machine);
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function isEqualTo($state)
    {
        return $this->_isEqualTo($state);
    }
}
