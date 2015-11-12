<?php

namespace FSM\State;

/**
 * State general logic trait
 *
 * @package FSM\State
 */
trait StateTrait
{
    /** @var  string */
    protected $name;

    /** @var  string */
    protected $type;


    /**
     * State name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * State type getter
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * If state identical with another
     *
     * @param StateInterface $comparedState
     * @return bool
     */
    public function isIdentical(StateInterface $comparedState)
    {
        return $this->getName() == $comparedState->getName() &&
            $this->getType() == $comparedState->getType();
    }

}
