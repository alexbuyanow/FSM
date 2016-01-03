<?php

return [
    'options' => [
        'resolver' => 'FSM\Resolver\DefaultResolver',
        'strict' => FSM\Machine\Machine::STRICT_ALL,
    ],

    'machines' => [
        'Context' => [
            FSM\Machine\MachineFactory::CONFIG_KEY_STATES => [
                'created' => ['type' => $typeRegular],
                'edited' => ['type' => $typeRegular],
                'active' => ['type' => $typeRegular],
                'inactive' => ['type' => $typeRegular],
                'deleted' => ['type' => $typeRegular],
            ],

            FSM\Machine\MachineFactory::CONFIG_KEY_TRANSITIONS => [
                [
                    'from' => 'created',
                    'to' => 'edited',
                    'signal' => 'edit',
                ],

                [
                    'from' => 'edited',
                    'to' => 'active',
                    'signal' => 'activate',
                ],
                [
                    'from' => 'edited',
                    'to' => 'inactive',
                    'signal' => 'deactivate',
                ],

                [
                    'from' => 'active',
                    'to' => 'deleted',
                    'signal' => 'delete',
                    'guard' => 'SimpleGuard',
                ],

                [
                    'from' => 'inactive',
                    'to' => 'deleted',
                    'signal' => 'delete',
                    'guard' => 'SimpleGuard',
                ],
            ],

            FSM\Machine\MachineFactory::CONFIG_KEY_LISTENERS => [
                [
                    'event'         => \FSM\Event\EventInterface::MACHINE_REFRESH_POST,
                    'listener'      => 'SimpleListener',
                ],
            ],
        ],
    ],
];
