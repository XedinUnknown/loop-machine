<?php

namespace XedinUnknown\LoopMachine;

use Iterator;
use SplObjectStorage;
use SplObserver;
use XedinUnknown\LoopMachine\Exception\InvalidStateExceptionInterface;
use XedinUnknown\LoopMachine\Exception\ExceptionInterface;
use Dhii\Data\ValueAwareInterface;
use Dhii\Util\String\StringableInterface;

/**
 * Common basic functionality for loop machines.
 *
 * @since [*next-version*]
 */
abstract class AbstractLoopMachine
{
    /**
     * The observer instances.
     *
     * @since [*next-version*]
     *
     * @var SplObjectStorage
     */
    protected $observers;

    /**
     * The machine's state.
     *
     * @since [*next-version*]
     *
     * @var int|string
     */
    protected $currentState;

    /**
     * The current iteration item.
     *
     * @since [*next-version*]
     *
     * @var mixed
     */
    protected $currentItem;

    /**
     * A list of states for loop machines of this type.
     *
     * @since [*next-version*]
     *
     * @var array
     */
    protected static $states = array(
        LoopMachineInterface::STATE_START   => null,
        LoopMachineInterface::STATE_LOOP    => null,
        LoopMachineInterface::STATE_END     => null,
    );

    /**
     * The index used internally to store object storage info for an observer.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected static $storageDataIndex = 'data';

    /**
     * The index used internally to store an observer instance.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected static $storageObjectIndex = 'obj';

    /**
     * Parameter-less constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
        $this->_detachAll();
    }

    /**
     * Retrieves the currently attached observers.
     *
     * @since [*next-version*]
     *
     * @return SplObserver[] The observer instances.
     */
    protected function _getObservers()
    {
        return $this->observers;
    }

    /**
     * Retrieves the current state of the machine.
     *
     * @since [*next-version*]
     *
     * @return StateInterface The current state.
     */
    protected function _getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * Sets the current state of the machine.
     *
     * @since [*next-version*]
     *
     * @param ValueAwareInterface $state The state of the machine.
     *
     * @return AbstractLoopMachine This instance.
     *
     * @throws InvalidStateExceptionInterface If the given state is not valid.
     */
    protected function _setCurrentState(ValueAwareInterface $state)
    {
        $this->_assertValidState($state);
        $this->_assertValidStateValue($state->getValue());
        $this->currentState = $state;

        return $this;
    }

    /**
     * Sets a state instance corresponding to the specified value as the current state.
     *
     * @since [*next-version*]
     *
     * @param int|string|bool|float $value The value of the state to set.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _setCurrentValue($value)
    {
        $state = $this->_getStateInstance($value);
        $this->_setCurrentState($state);

        return $this;
    }

    /**
     * A list of all possible states of this machine.
     *
     * @since [*next-version*]
     *
     * @return array A list of states.
     */
    protected function _getStates()
    {
        return static::$states;
    }

    /**
     * Determines whether a state is valid for this machine.
     *
     * @since [*next-version*]
     *
     * @param StateInterface $state The state to validate.
     * @return bool True if the given state is valid; false otherwise.
     */
    protected function _isValidStateValue($state)
    {
        $states = $this->_getStates();
        $values = array_keys($states);
        $isValid = in_array($state, $values, true);

        return $isValid;
    }

    /**
     * Throws an exception if the given state value is not valid.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The state value that must be valid.
     * @throws InvalidStateExceptionInterface If state value is invalid.
     */
    protected function _assertValidStateValue($value)
    {
        if (!$this->_isValidStateValue($value)) {
            throw $this->_createInvalidStateException(sprintf('State value is not valid'), $value, $this);
        }
    }

    /**
     * Determines if a state is valid.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The state to validate.
     * @return bool True if the state is valid; false otherwise.
     */
    protected function _isValidState($state)
    {
        return $state instanceof ValueAwareInterface;
    }

    /**
     * Throws an exception if the given state is not valid.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The state that must be valid.
     * @throws InvalidStateExceptionInterface If state is invalid.
     */
    protected function _assertValidState($state)
    {
        if (!$this->_isValidState($state)) {
            throw $this->_createInvalidStateException(sprintf('State is not valid'), $state, $this);
        }
    }

    /**
     * Creates an exception which signals that an invalid state is being set.
     *
     * @since [*next-version*]
     *
     * @param string $message The message of the exception.
     * @param mixed $state The state which was attempted.
     * @param LoopMachineInterface $machine The machine, on which the state was attempted to be set.
     * @param \Exception $previous A previous, inner exception, if any.
     *
     * @return InvalidStateExceptionInterface The new exception.
     */
    abstract protected function _createInvalidStateException($message, $state, LoopMachineInterface $machine = null, \Exception $previous = null);

    /**
     * Creates a generic loop machine exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The message of the exception.
     * @param \Exception $previous A previous, inner exception, if any.
     *
     * @return ExceptionInterface The new exception.
     */
    abstract protected function _createException($message, \Exception $previous = null);

    /**
     * Retrieves the current iteration item.
     *
     * @since [*next-version*]
     *
     * @return mixed The item that the machine is on at this moment.
     */
    protected function _getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     * Sets the current iteration item.
     *
     * @since [*next-version*]
     *
     * @param mixed $current The current item in the loop.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _setCurrentItem($current)
    {
        $this->currentItem = $current;

        return $this;
    }

    /**
     * Attaches an observer to this subject.
     *
     * @since [*next-version*]
     *
     * @param SplObserver The observer to attach.
     * @param int $priority The priority: higher numbers indicate earlier notification.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _attach(SplObserver $observer, $priority = 0)
    {
        $this->observers[$observer] = $priority;

        return $this;
    }

    /**
     * Detaches an observer from this subject.
     *
     * @since [*next-version*]
     *
     * @param SplObserver $observer The observer to detach.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _detach(SplObserver $observer)
    {
        unset($this->observers[$observer]);

        return $this;
    }

    /**
     * Detaches all observers.
     *
     * @since [*next-version*]
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _detachAll()
    {
        $this->observers = new SplObjectStorage();

        return $this;
    }

    /**
     * Notifies all attached observers.
     *
     * @since [*next-version*]
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _notify()
    {
        $sortedObservers = $this->_getSortedObservers();

        foreach ($sortedObservers as $_observer) {
            $_observer->update($this);
        }

        return $this;
    }

    /**
     * Processes a given iterable.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $iterable The iterable.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _process($iterable)
    {
        $this->_processStart($iterable);

        foreach ($iterable as $_item) {
            $this->_processLoop($iterable, $_item);
        }

        $this->_processEnd($iterable);

        return $this;
    }

    /**
     * Starts the processing of the iterables.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $iterable The iterable.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _processStart($iterable)
    {
        $this->_setCurrentValue(static::STATE_START)
            ->_setCurrentItem(null)
            ->_notify();

        return $this;
    }

    /**
     * Performs the processing of an item in the loop.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $iterable The iterable.
     *
     * @param mixed $item The loop item.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _processLoop($iterable, $item)
    {
        $this->_setCurrentValue(static::STATE_LOOP)
            ->_setCurrentItem($item)
            ->_notify();

        return $this;
    }

    /**
     * Ends the processing of the iterable.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $iterable The iterable.
     *
     * @return AbstractLoopMachine This instance.
     */
    protected function _processEnd($iterable)
    {
        $this->_setCurrentValue(static::STATE_END)
            ->_setCurrentItem(null)
            ->_notify();

        return $this;
    }

    /**
     * Retrieves the observers sorted by priority.
     *
     * @since [*next-version*]
     *
     * @return SplObserver[] A list of observers in FIFO order.
     */
    protected function _getSortedObservers()
    {
        $array = $this->_splObjectStorageToArray($this->observers);
        $idxData = static::$storageDataIndex;
        $idxObject = static::$storageObjectIndex;

        usort($array, function($a, $b) use ($idxData) {
            return (int) $a[$idxData] < (int) $b[$idxData];
        });

        return array_map(function($item) use ($idxObject) {
            return $item[$idxObject];
        }, $array);
    }

    /**
     * Transforms an SplObjectStorage instance with integer values into an associative array.
     *
     * @since [*next-version*]
     *
     * @param SplObjectStorage $storage The object storage. Must have integer-like values.
     *
     * @return array A list of observer data maps, where each element has the following keys:
     * `obj` - The observer instance;
     * `data` - The data associated with the observer, as a result of {@see SplObjectStorage::getInfo()} for that observer.
     */
    protected function _splObjectStorageToArray(SplObjectStorage $storage)
    {
        $result = array();
        foreach ($storage as $_observer) {
            /* @var $_observer SplObserver */
            $result[] = array(
                static::$storageObjectIndex => $_observer,
                static::$storageDataIndex   => $storage->getInfo()
            );
        }
        return $result;
    }

    /**
     * Converts a machine state to its simplest possible representation.
     *
     * @since [*next-version*]
     *
     * @param mixed $state The original value.
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
     * Creates a new instance of a state, representing the specified value.
     *
     * @since [*next-version*]
     *
     * @param int|string|bool|float $value The value for the new state.
     *
     * @return ValueAwareInterface The new state instance.
     */
    abstract protected function _createStateInstance($value);

    /**
     * Gets an instance of a state with the specified value.
     *
     * @since [*next-version*]
     *
     * @param int|string|bool|float $value The value of the state.
     * @return ValueAwareInterface The state instance.
     *
     * @throws InvalidStateExceptionInterface If the value is not valid.
     */
    protected function _getStateInstance($value)
    {
        if (!isset(static::$states[$value])) {
            $this->_assertValidStateValue($value);
            $state = $this->_createStateInstance($value);
            static::$states[$value] = $state;
        }

        return static::$states[$value];
    }
}
