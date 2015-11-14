<?php

namespace FSM\Resolver;

use FSM\ContextInterface;

/**
 * Config resolver interface
 *
 * @package FSM\Resolver
 */
interface ResolverInterface
{

    /**
     * Is exists machine config for this context
     *
     * @param ContextInterface $context
     * @return boolean
     */
    public function isContained(ContextInterface $context);

    /**
     * Get machine config for this context
     *
     * @param ContextInterface $context
     * @return array
     */
    public function getConfig(ContextInterface $context);

    /**
     * Get machine config name for this context
     *
     * @param ContextInterface $context
     * @return string
     */
    public function getConfigName(ContextInterface $context);
}
