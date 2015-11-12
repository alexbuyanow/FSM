<?php

namespace FSM\State;

/**
 * Regular state class
 *
 * @package FSM\State
 */
class StateRegular implements StateInterface
{
    use StateTrait;


    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type = self::TYPE_REGULAR)
    {
        if ($type != self::TYPE_REGULAR) {
            $message = sprintf('Incorrect state type %s for this class. Must be %s', $type, self::TYPE_REGULAR);
            throw new Exception\InvalidStateConfig($message);
        }

        $this->name = $name;
        $this->type = $type;
    }

}
