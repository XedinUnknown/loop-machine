<?php

namespace XedinUnknown\LoopMachine\TestStub;

use SplObserver;
use SplSubject;

/**
 * A mock of an observer used for testing.
 *
 * Direct mocking of {@see SplObserver} impossible due to {@link https://bugs.php.net/bug.php?id=69811 bug},
 * which causes tests run on PHP 7.0 and above to fail with xpmock.
 *
 * @since [*next-version*]
 */
class Observer implements SplObserver
{
    /**
     * The callback that is executed when this instance gets updated.
     *
     * @since [*next-version*]
     *
     * @var callable
     */
    protected $updateCallback;

    /**
     * @since [*next-version*]
     *
     * @param callable $callback This will get executed when this instance is updated.
     */
    public function __construct($callback)
    {
        $this->updateCallback = $callback;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function update(SplSubject $subject)
    {
        call_user_func_array($this->updateCallback, array($subject));
    }
}
