<?php
/**
 * Default global config for FSM module
 * Should be merged with outer FSM config
 */

return [

    /** Module options section */
    'options'           => [

        /** Machine config resolver class option */
        'resolver'          => 'FSM\Resolver\DefaultResolver',

        /** Machine strict mode. See FSM\Machine\MachineInterface */
        'strict'            => FSM\Machine\Machine::STRICT_NONE,
    ],

    /** Machines config section */
    'machines'          => [],

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
