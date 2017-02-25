<?php

namespace XedinUnknown\LoopMachine\Exception;

use XedinUnknown\LoopMachine\LoopMachineInterface;

/**
 * An exception that indicates that a state is invalid.
 *
 * @since [*next-version*]
 */
class InvalidStateException extends AbstractInvalidStateException implements InvalidStateExceptionInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @param mixed $state The state that was determined to be invalid.
     * @param LoopMachineInterface $machine The machine related to this exception, if any.
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null, $state = null, LoopMachineInterface $machine = null)
    {
        parent::__construct($message, $code, $previous);

        $this->_setState($state);
        if (!is_null($machine)) {
            $this->_setMachine($machine);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getState()
    {
        return $this->_getState();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMachine()
    {
        return $this->_getMachine();
    }
}
