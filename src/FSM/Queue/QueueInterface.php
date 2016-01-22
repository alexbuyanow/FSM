<?php

namespace FSM\Queue;

/**
 * Queue interface
 *
 * @package FSM\Queue
 */
interface QueueInterface extends \Countable
{
    /**
     * Adds an element to the queue
     *
     * @param mixed $value
     * @return void
     */
    public function enqueue($value);

    /**
     * Dequeues an element from the queue
     *
     * @return mixed
     */
    public function dequeue();
}
