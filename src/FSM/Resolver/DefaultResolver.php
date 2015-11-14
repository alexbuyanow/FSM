<?php

namespace FSM\Resolver;

use FSM\ContextInterface;

/**
 * Default machine config resolver
 *
 * @package FSM\Resolver
 */
class DefaultResolver implements ResolverInterface
{
    /** @var  array */
    protected $config;


    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Is exists machine config for this context
     *
     * @param ContextInterface $context
     * @return boolean
     */
    public function isContained(ContextInterface $context)
    {
        return array_key_exists(
            $this->getContextClass($context),
            $this->config
        );
    }

    /**
     * Get machine config for this context
     *
     * @param ContextInterface $context
     * @return array
     * @throw Exception\ConfigNotFoundException
     */
    public function getConfig(ContextInterface $context)
    {
        if(!$this->isContained($context))
        {
            $message = sprintf(
                'Config for context named %s not found',
                $this->getContextClass($context)
            );
            throw new Exception\ConfigNotFoundException($message);
        }

        return $this->config[$this->getContextClass($context)];
    }

    /**
     * Get machine config name for this context
     *
     * @param ContextInterface $context
     * @return array
     */
    public function getConfigName(ContextInterface $context)
    {
        return $this->getContextClass($context);
    }

    /**
     * Get Context class name
     *
     * @param ContextInterface $context
     * @return string
     */
    private function getContextClass(ContextInterface $context)
    {
        return get_class($context);
    }
}
