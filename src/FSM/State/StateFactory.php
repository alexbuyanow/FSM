<?php

namespace FSM\State;

/**
 * State factory
 *
 * @package FSM\State
 */
class StateFactory implements StateFactoryInterface
{
    /**
     * Keys in single state config
     */
    const CONFIG_KEY_TYPE           = 'type';

    /**
     * Available state types
     *
     * @var array
     */
    private $stateTypes = [
        StateInterface::TYPE_REGULAR        => 'StateRegular',
    ];

    /** @var  array */
    private $config = [];

    /** @var StateInterface[] */
    private $states = [];


    public function __construct(array $config)
    {
        $this->config               = $config;
    }

    /**
     * State object getting
     *
     * @param string $name
     * @return StateInterface
     */
    public function getState($name)
    {
        if(!array_key_exists($name, $this->config))
        {
            $message = sprintf('State with name "%s" is not found in config', $name);
            throw new Exception\StateNotFound($message);
        }

        if(!array_key_exists($name, $this->states))
        {
            $stateConfig = $this->config[$name];
            if(
                !array_key_exists(self::CONFIG_KEY_TYPE, $stateConfig) ||
                !array_key_exists($stateConfig[self::CONFIG_KEY_TYPE], $this->stateTypes)
            )
            {
                $message = 'State config has not or has undefined required key "type"';
                throw new Exception\InvalidStateConfig($message);
            }

            $stateClassName = __NAMESPACE__ . '\\' . $this->stateTypes[$stateConfig[self::CONFIG_KEY_TYPE]];

            $this->states[$name] = new $stateClassName(
                $name,
                $stateConfig[self::CONFIG_KEY_TYPE]
            );
        }

        return $this->states[$name];
    }
}
