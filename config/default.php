<?php
/**
 * Default global config for FSM module
 * Should be merged with outer FSM config
 */

use FSM\FSMLocator;

return [

    /** Module options section */
    FSMLocator::CONFIG_KEY_OPTIONS      => [

        /** Machine config resolver class option */
        FSMLocator::OPTIONS_KEY_RESOLVER    => FSM\Resolver\DefaultResolver::class,

        /** Machine strict mode. See FSM\Machine\MachineInterface */
        FSMLocator::OPTIONS_KEY_STRICT      => FSM\Machine\Machine::STRICT_NONE,
    ],

    /** Machines config section */
    FSMLocator::CONFIG_KEY_MACHINES     => [],

];

/** Simple machine config example */
/*
'machine_name'  => [

    'states'        => [

        'state1' =>  [ 'type'  => FSM\State\StateInterface::TYPE_REGULAR ],
        or
        'stateN' =>  [  FSM\State\StateFactory::CONFIG_KEY_TYPE  => FSM\State\StateInterface::TYPE_REGULAR ],

    ],

    'transitions'   => [
        [
            'from'          => 'state1',        //Initial transition state
            'to'            => 'stateN',        //Final transition state
            'signal'        => 'signal',        //Signal, starting transition (optional)
            'guard'         => 'guard_name',    //Guard name in DI container (optional)
        ]
    ],

    'listeners'     => [
        [
            'event'         => 'event.name'     //Event name (see EventInterface)
            'listener'      => 'listener_name'  //Listener name in DI container
        ]
    ],

],
*/
