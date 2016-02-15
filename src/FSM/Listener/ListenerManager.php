<?php

namespace FSM\Listener;

use FSM\Container\ContainerInterface;

/**
 * Listener manager
 *
 * @package FSM\Guard
 */
class ListenerManager implements ListenerManagerInterface
{
    /** @var string  */
    private $methodCallable = 'listen';


    /** @var  ContainerInterface */
    private $container;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets named listener object
     *
     * @param string $name
     * @return ListenerInterface
     */
    public function getListener($name)
    {
        if(!$this->container->has($name))
        {
            $message = sprintf(
                '"%s" listener is not found in DI container',
                $name
            );
            throw new Exception\ListenerNotFoundException($message);
        }

        $listener = $this->container->get($name);

        if (!($listener instanceof ListenerInterface)) {
            $message = sprintf(
                'Listener "%s" must be object and instance of ListenerInterface',
                is_object($listener) ? get_class($listener) : gettype($listener)
            );
            throw new Exception\InvalidListenerException($message);
        }

        return $listener;
    }

    /**
     * Gets callable for named listener object
     *
     * @param string $name
     * @return callable
     */
    public function getListenerCallable($name)
    {
        return [$this->getListener($name), $this->methodCallable];
    }
}
