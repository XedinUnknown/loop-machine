<?php

namespace XedinUnknown\LoopMachine;

use Dhii\Data\ValueAwareInterface;
use SplObserver;
use XedinUnknown\LoopMachine\Exception\Exception;
use XedinUnknown\LoopMachine\Exception\InvalidStateException;

/**
 * A state machine that processes a list of items and informs observers.
 *
 * @since [*next-version*]
 */
class LoopMachine extends AbstractLoopMachine implements LoopMachineInterface
{
    /**
     * @since [*next-version*]
     */
    public function __construct()
    {
        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCurrentState()
    {
        return $this->_getCurrentState();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCurrentItem()
    {
        return $this->_getCurrentItem();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function attach(SplObserver $observer, $priority = 0)
    {
        $this->_attach($observer, $priority);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function detach(SplObserver $observer)
    {
        $this->_detach($observer);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function notify()
    {
        $this->_notify();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return LoopMachine This instance.
     */
    public function process($iterable)
    {
        $this->_process($iterable);

        return $this;
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
    protected function _createStateInstance($value)
    {
        return new State($value);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStates()
    {
        $stateValues = array_keys($this->_getStates());
        $states      = array_map(array($this, '_getStateInstance'), $stateValues);

        return $states;
    }
}
