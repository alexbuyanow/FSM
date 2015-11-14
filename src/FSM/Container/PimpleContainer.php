<?php

namespace FSM\Container;

use Pimple\Container;

/**
 * DI container via Pimple
 *
 * @package FSM\Container
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
     * @return mixed
     */
    public function get($name)
    {
        return $this->diContainer[$name];
    }
}
