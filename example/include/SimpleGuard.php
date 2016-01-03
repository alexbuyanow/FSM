<?php

class SimpleGuard implements \FSM\Guard\GuardInterface
{
    public function isSatisfied(\FSM\ContextInterface $context)
    {
        return $context->getContextState() == 'active';
    }
}