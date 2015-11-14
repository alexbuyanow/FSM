<?php

namespace FSM\Resolver;

use FSM\ContextInterface;

/**
 * Default config resolver tests
 *
 * @package FSM\Resolver
 */
class DefaultResolverTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $config = [];

    /**
     * Context contained in config
     *
     * @var ContextInterface
     */
    private $contextMockContained;

    /**
     * Context not contained in config
     *
     * @var ContextInterface
     */
    private $contextMockNotContained;


    public function testIsContained()
    {
        $resolver = new DefaultResolver($this->config);

        $this->assertTrue($resolver->isContained($this->contextMockContained));
        $this->assertFalse($resolver->isContained($this->contextMockNotContained));
    }

    public function testGetConfig()
    {
        $resolver = new DefaultResolver($this->config);
        $config  = $resolver->getConfig($this->contextMockContained);

        $this->assertTrue(is_array($config));
    }

    public function testGetConfigNotFound()
    {
        $resolver = new DefaultResolver($this->config);

        $this->setExpectedException(__NAMESPACE__ . '\Exception\ConfigNotFoundException');
        $resolver->getConfig($this->contextMockNotContained);
    }

    public function testGetConfigName()
    {
        $resolver = new DefaultResolver($this->config);

        $this->assertEquals('TestContext', $resolver->getConfigName($this->contextMockContained));
    }


    protected function setUp()
    {
        $this->config = [
            'TestContext'   => [],
        ];

        $this->contextMockContained = $this->getMock(
            'FSM\ContextInterface',
            [],
            [],
            'TestContext'
        );

        $this->contextMockNotContained = $this->getMock(
            'FSM\ContextInterface',
            [],
            [],
            'AnotherContext'
        );
    }

    protected function tearDown()
    {
        $this->config = [];
        unset($this->contextMockContained);
        unset($this->contextMockNotContained);
    }
}
