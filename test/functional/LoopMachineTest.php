<?php

namespace Dhii\Di\FuncTest;

use Xpmock\TestCase;
use SplObserver;
use XedinUnknown\LoopMachine\LoopMachine;
use XedinUnknown\LoopMachine\LoopMachineInterface;

/**
 * Tests {@see \XedinUnknown\LoopMachine\LoopMachine}.
 *
 * @since [*next-version*]
 */
class LoopMachineTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'XedinUnknown\\LoopMachine\\LoopMachine';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return LoopMachine
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new(true);

        return $mock;
    }

    /**
     * Creates an observer.
     *
     * @since [*next-version*]
     *
     * @param callable $callback The callback that will get invoked when the observer gets updated.
     *
     * @return SplObserver The new observer instance.
     */
    public function createObserver($callback)
    {
        $mock = $this->mock('SplObserver')
                ->update($callback)
                ->new();

        return $mock;
    }

    /**
     * Tests that the loop machine can correctly loop and notify observers.
     *
     * @since [*next-version*]
     */
    public function testMainFunctionality()
    {
        $subject = $this->createInstance();
        $data = array(
            'apple',
            'banana',
            'orange'
        );

        $items = array();
        $states = array();
        $observer = $this->createObserver(function(LoopMachineInterface $machine) use (&$items, &$states) {
            $states[] = $machine->getCurrentState()->getValue();

            if ($machine->getCurrentState()->isEqualTo(LoopMachineInterface::STATE_LOOP)) {
                $items[] = $machine->getCurrentItem();
            }
        });
        $subject->attach($observer);
        $subject->process($data);

        $this->assertEquals($data, $items, 'Machine did not iterate over all of the data correctly', $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(array(
            LoopMachineInterface::STATE_START,
            LoopMachineInterface::STATE_LOOP,
            LoopMachineInterface::STATE_LOOP,
            LoopMachineInterface::STATE_LOOP,
            LoopMachineInterface::STATE_END,
        ), $states, 'Machine did not enter all the states correctly');
    }
}
