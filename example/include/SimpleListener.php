<?php

class SimpleListener implements \FSM\Listener\ListenerInterface
{
    public function listen(\FSM\Event\EventInterface $event)
    {
//        echo 'Event: ', get_class($event), $event->getSignal(), "\n";
        echo sprintf(
            'Event: name: %s, class: %s;',
            $event->getName(),
            get_class($event)
        ), "\n";
    }
}