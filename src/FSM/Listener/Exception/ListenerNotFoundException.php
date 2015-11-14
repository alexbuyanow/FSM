<?php

namespace FSM\Listener\Exception;

/**
 * Throws when listener is not found (for example, in DI)
 *
 * @package FSM\Listener\Exception
 */
class ListenerNotFoundException extends \InvalidArgumentException
{

}
