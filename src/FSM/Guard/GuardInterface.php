<?php

namespace FSM\Guard;

use FSM\ContextInterface;

/**
 * Machine guard object class interface
 *
 * @package FSM\Guard
 */
interface GuardInterface
{
    /**
     * Says is guard condition true
     *
     * @param ContextInterface $context
     * @return boolean
     */
    public function isSatisfied(ContextInterface $context);
}
