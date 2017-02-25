<?php

namespace XedinUnknown\LoopMachine;

use XedinUnknown\LoopMachine\Exception\ExceptionInterface;

/**
 * Common functionality for machine states.
 *
 * @since [*next-version*]
 */
abstract class AbstractState
{
    /**
     * The value of this state.
     *
     * @since [*next-version*]
     *
     * @var int|string|bool|float
     */
    protected $value;

    /**
     * Parameter-less constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * Retrieves the value of this state.
     *
     * @since [*next-version*]
     *
     * @return int|string|bool|float The value of this state.
     */
    protected function _getValue()
    {
        return $this->value;
    }

    /**
     * Assigns the value to this state.
     *
     * @since [*next-version*]
     *
     * @param int|string|bool|float $value The value.
     *
     * @return AbstractState This instance.
     */
    protected function _setValue($value)
    {
        $this->_assertValueValid($value);
        $this->value = $value;

        return $this;
    }

    /**
     * Determines whether this state is equivalent to another state.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The state to compare this state to.
     *
     * @return bool True if this state is equivalent to the given state; false otherwise.
     */
    protected function _isEqualTo($state)
    {
        $state        = $this->_resolveState($state);
        $currentState = $this->_getValue();

        try {
            $this->_assertValueValid($state);
        } catch (\Exception $e) {
            throw $this->_createInvalidStateException(sprintf('Could not determine equivalence state "%1$s"', $currentState), $state, null, $e);
        }

        return !$this->_compareStateValues($currentState, $state);
    }

    /**
     * Compares two scalar values that represent states.
     *
     * @since [*next-version*]
     *
     * @param int|string|bool|float $a A scalar value.
     * @param int|string|bool|float $b A scalar value.
     *
     * @throws \InvalidArgumentException If at least one of the parameters is not scalar.
     *
     * @return int Returns < 0 if $a is less than $b; > 0 if $a is greater than $b; or 0 if they are equal.
     */
    protected function _compareStateValues($a, $b)
    {
        try {
            $this->_assertValueValid($a);
            $this->_assertValueValid($b);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Could not compare state values', 0, $e);
        }

        if (is_string($a)) {
            $b = (string) $b;

            return strcmp($a, $b);
        }

        if ($a < $b) {
            return -1;
        }

        return (int) ($a > $b);
    }

    /**
     * Creates a generic loop machine exception.
     *
     * @since [*next-version*]
     *
     * @param string     $message  The message of the exception.
     * @param \Exception $previous A previous, inner exception, if any.
     *
     * @return ExceptionInterface The new exception.
     */
    abstract protected function _createException($message, \Exception $previous = null);

    /**
     * Creates an exception which signals that an invalid state is being set.
     *
     * @since [*next-version*]
     *
     * @param string               $message  The message of the exception.
     * @param mixed                $state    The state which was attempted.
     * @param LoopMachineInterface $machine  The machine, on which the state was attempted to be set.
     * @param \Exception           $previous A previous, inner exception, if any.
     *
     * @return InvalidStateExceptionInterface The new exception.
     */
    abstract protected function _createInvalidStateException($message, $state, LoopMachineInterface $machine = null, \Exception $previous = null);

    /**
     * Converts a machine state to its simplest possible representation.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The original value.
     *
     * @return mixed The resolved value.
     */
    protected function _resolveState($state)
    {
        if (is_callable($state)) {
            $state = call_user_func_array($state, array());
            $state = $this->_resolveValue($state);
        }

        if ($state instanceof ValueAwareInterface) {
            $state = $state->getValue();
            $state = $this->_resolveValue($state);
        }

        if ($state instanceof StringableInterface) {
            $state = (string) $state;
        }

        return $state;
    }

    /**
     * Determines whether a value is valid.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The value to validate.
     *
     * @return bool True if the specified value is valid; false otherwise.
     */
    protected function _isValueValid($value)
    {
        return is_scalar($value);
    }

    /**
     * Throws an exception if the given value is invalid.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The value that must be valid.
     *
     * @throws ExceptionInterface If value is invalid.
     */
    protected function _assertValueValid($value)
    {
        if (!$this->_isValueValid($value)) {
            throw $this->_createException('Value is invalid');
        }
    }
}
