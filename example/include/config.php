<?php

use FSM\FSMLocator;
use FSM\Listener\ListenerManager;
use FSM\Machine\MachineFactory;
use FSM\State\StateFactory;
use FSM\Transition\TransitionFactory;

return [
    FSMLocator::CONFIG_KEY_OPTIONS  => [
        FSMLocator::OPTIONS_KEY_RESOLVER  => FSM\Resolver\DefaultResolver::class,
        FSMLocator::OPTIONS_KEY_STRICT    => FSM\Machine\Machine::STRICT_ALL,
    ],

    FSMLocator::CONFIG_KEY_MACHINES => [
        'Context' => [
            MachineFactory::CONFIG_KEY_STATES       => [
                'created'   => [StateFactory::CONFIG_KEY_TYPE => $typeRegular],
                'edited'    => [StateFactory::CONFIG_KEY_TYPE => $typeRegular],
                'active'    => [StateFactory::CONFIG_KEY_TYPE => $typeRegular],
                'inactive'  => [StateFactory::CONFIG_KEY_TYPE => $typeRegular],
                'deleted'   => [StateFactory::CONFIG_KEY_TYPE => $typeRegular],
            ],

            MachineFactory::CONFIG_KEY_TRANSITIONS  => [
                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM    => 'created',
                    TransitionFactory::CONFIG_KEY_STATE_TO      => 'edited',
                    TransitionFactory::CONFIG_KEY_SIGNAL        => 'edit',
                ],

                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM    => 'edited',
                    TransitionFactory::CONFIG_KEY_STATE_TO      => 'active',
                    TransitionFactory::CONFIG_KEY_SIGNAL        => 'activate',
                ],
                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM    => 'edited',
                    TransitionFactory::CONFIG_KEY_STATE_TO      => 'inactive',
                    TransitionFactory::CONFIG_KEY_SIGNAL        => 'deactivate',
                ],

                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM    => 'active',
                    TransitionFactory::CONFIG_KEY_STATE_TO      => 'deleted',
                    TransitionFactory::CONFIG_KEY_SIGNAL        => 'delete',
                    TransitionFactory::CONFIG_KEY_GUARD         => 'SimpleGuard',
                ],

                [
                    TransitionFactory::CONFIG_KEY_STATE_FROM    => 'inactive',
                    TransitionFactory::CONFIG_KEY_STATE_TO      => 'deleted',
                    TransitionFactory::CONFIG_KEY_SIGNAL        => 'delete',
                    TransitionFactory::CONFIG_KEY_GUARD         => 'SimpleGuard',
                ],
            ],

            MachineFactory::CONFIG_KEY_LISTENERS    => [
                [
                    ListenerManager::CONFIG_KEY_EVENT       => FSM\Event\Event::MACHINE_REFRESH_POST,
                    ListenerManager::CONFIG_KEY_LISTENER    => SimpleListener::class,
                ],
            ],
        ],
    ],
];
