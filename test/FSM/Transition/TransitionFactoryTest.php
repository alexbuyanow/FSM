<?php
namespace FSM\Transition;

use FSM\Guard\GuardManagerInterface;
use FSM\State\StateFactory;
use FSM\State\StateFactoryInterface;
use FSM\State\StateInterface;

/**
 * Transition factory test
 *
 * @package FSM\Transition
 */
class TransitionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTransition()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_FROM    => 'state_from',
            TransitionFactory::CONFIG_KEY_STATE_TO      => 'state_to',
            TransitionFactory::CONFIG_KEY_SIGNAL        => 'some_signal',
        ];

        $stateMock = $this->getMock(
            StateInterface::class,
            ['getName', 'getType', 'isIdentical']
        );
        $stateFactoryMock = $this->getMock(
            StateFactoryInterface::class,
            ['getState'],
            [$this->getStateConfig()]
        );
        $stateFactoryMock
            ->expects($this->exactly(2))
            ->method('getState')
            ->willReturn($stateMock);

        $guardManagerMock = $this->getMock(GuardManagerInterface::class);

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardManagerMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardManagerMock);
        $transition        = $transitionFactory->getTransition($config);

        $this->assertInstanceOf(TransitionInterface::class, $transition);
        $this->assertInstanceOf(StateInterface::class, $transition->getStateFrom());
        $this->assertInstanceOf(StateInterface::class, $transition->getStateTo());
        $this->assertEquals('some_signal', $transition->getSignal());
    }

    public function testGetTransitionHasNotFromState()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_TO  => 'state_to',
            TransitionFactory::CONFIG_KEY_SIGNAL    => 'some_signal',
        ];

        $stateFactoryMock = $this->getMock(
            StateFactoryInterface::class,
            ['getState'],
            [$this->getStateConfig()]
        );

        $guardManagerMock = $this->getMock(GuardManagerInterface::class);

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardManagerMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardManagerMock);

        $this->setExpectedException(Exception\InvalidTransitionConfig::class);

        $transitionFactory->getTransition($config);
    }

    public function testGetTransitionHasNotToState()
    {
        $config = [
            TransitionFactory::CONFIG_KEY_STATE_FROM    => 'state_from',
            TransitionFactory::CONFIG_KEY_SIGNAL        => 'some_signal',
        ];

        $stateFactoryMock = $this->getMock(
            StateFactoryInterface::class,
            ['getState'],
            [$this->getStateConfig()]
        );

        $guardManagerMock = $this->getMock(GuardManagerInterface::class);

        /** @var StateFactoryInterface $stateFactoryMock */
        /** @var GuardManagerInterface $guardManagerMock */
        $transitionFactory = new TransitionFactory($stateFactoryMock, $guardManagerMock);

        $this->setExpectedException(Exception\InvalidTransitionConfig::class);

        $transitionFactory->getTransition($config);
    }


    private function getStateConfig()
    {
        return ['state' => [StateFactory::CONFIG_KEY_TYPE => StateInterface::TYPE_REGULAR]];
    }
}
