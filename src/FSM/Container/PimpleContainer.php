<?php

namespace FSM\Container;

use Pimple\Container;

/**
 * DI container via Pimple
 */
class PimpleContainer implements ContainerInterface
{
    /** @var  Container */
    private $diContainer;


    /**
     * @param Container $diContainer
     */
    public function __construct(Container $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * Gets a parameter or an object
     *
     * @param string $name
     * @throws Exception\NotFoundException
     * @return mixed
     */
    public function get($name)
    {
        if(!$this->has($name))
        {
            $message = sprintf(
                'Entry with name "%s" is not found in container',
                $name
            );
            throw new Exception\NotFoundException($message);
        }

        return $this->diContainer[$name];
    }

    /**
     * Check if object sets in container
     *
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->diContainer[$name]);
    }
}
