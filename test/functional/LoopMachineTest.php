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

        $states = array();
        $observer = $this->createObserver(function(LoopMachineInterface $machine) use (&$states) {
            if ($machine->getCurrentState()->isEqualTo(LoopMachineInterface::STATE_LOOP)) {
                $states[] = $machine->getCurrentItem();
            }
        });
        $subject->attach($observer);
        $subject->process($data);

        $this->assertEquals($data, $states, 'Machine did not iterate over all of the data correctly', $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }
}
