<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

class TransitionTableTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorEmptyConfig()
    {
        $transitionFactoryMock = $this->getMock('FSM\Transition\TransitionFactoryInterface');

        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidTransitionConfig');

        /** @var TransitionFactoryInterface $transitionFactoryMock */
        new TransitionTable($transitionFactoryMock, []);
    }

    public function testFindTransitions()
    {
        $stateMock = $this->getMock(
            'FSM\State\StateInterface',
            ['isIdentical', 'getName', 'getType']
        );
        $stateMock
            ->expects($this->once())
            ->method('isIdentical')
            ->willReturn(true);

        $transitionMock = $this->getMock(
            'FSM\Transition\TransitionInterface',
            ['getStateFrom', 'getStateTo', 'getSignal', 'getGuard', 'isDirect', 'hasGuard']
        );
        $transitionMock
            ->expects($this->once())
            ->method('getStateFrom')
            ->willReturn($stateMock);
        $transitionMock
            ->expects($this->once())
            ->method('getSignal')
            ->willReturn('test_signal');

        $transitionFactoryMock = $this->getMock(
            'FSM\Transition\TransitionFactoryInterface',
            ['getTransition']
        );
        $transitionFactoryMock
            ->expects($this->once())
            ->method('getTransition')
            ->willReturn($transitionMock);

        /** @var TransitionFactoryInterface $transitionFactoryMock */
        /** @var StateInterface $stateMock */
        $transitionTable = new TransitionTable($transitionFactoryMock, [['test_array']]);
        $transitions     = $transitionTable->findTransitions($stateMock, 'test_signal');

        $this->assertEquals(1, count($transitions));
        $this->assertContainsOnlyInstancesOf('FSM\Transition\TransitionInterface', $transitions);

    }
}
