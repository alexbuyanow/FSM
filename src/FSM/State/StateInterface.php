<?php

namespace FSM\State;

/**
 * General state interface
 *
 * @package FSM\State
 */
interface StateInterface
{
    /**
     * State types
     */
    const TYPE_REGULAR          = 'regular';
    const TYPE_SUB_MACHINE      = 'sub_machine';


    /**
     * State name getter
     *
     * @return string
     */
    public function getName();

    /**
     * State type getter
     *
     * @return string
     */
    public function getType();

    /**
     * If state identical with given
     *
     * @param StateInterface $comparedState
     * @return bool
     */
    public function isIdentical(StateInterface $comparedState);
}
