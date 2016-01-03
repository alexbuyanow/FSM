<?php

class Context implements FSM\ContextInterface
{
    private $uid = 'contextUID';
    private $state;

    public function getContextUid()
    {
        return $this->uid;
    }

    public function getContextState()
    {
        return $this->state;
    }

    public function setContextState($state)
    {
        $this->state = $state;
    }
}
