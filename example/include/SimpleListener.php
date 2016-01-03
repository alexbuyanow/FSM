<?php

class SimpleListener implements \FSM\Listener\ListenerInterface
{
    public function listen(\FSM\Event\EventInterface $event)
    {
        echo 'Event: ', get_class($event), $event->getSignal(), "\n";
    }
}