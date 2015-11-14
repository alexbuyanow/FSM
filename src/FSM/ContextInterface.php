<?php

namespace FSM;

/**
 * General interface for machines statefull context
 *
 * @package FSM
 */
interface ContextInterface
{

    /**
     * Gets unique context ID for machine
     *
     * @return string
     */
    public function getContextUid();

    /**
     * Gets context state as string
     *
     * @return string
     */
    public function getContextState();

    /**
     * Sets context state as string
     *
     * @param string $state
     * @return void
     */
    public function setContextState($state);
}