<?php

require_once __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/include/Context.php';
require __DIR__ . '/include/SimpleGuard.php';
require __DIR__ . '/include/SimpleListener.php';


$cr = "\n";
$typeRegular = FSM\State\StateInterface::TYPE_REGULAR;
$config = require __DIR__ . '/include/config.php';


$context = new Context();
$context->setContextState('created');

$di                     = new \Pimple\Container();
$di['SimpleGuard']      = function($container){
    return new SimpleGuard();
};
$di['SimpleListener']   = function($container){
    return new SimpleListener();
};
$container              = new \FSM\Container\PimpleContainer($di);


$machineFactory = new FSM\FSMLocator($config, $container);
$machine = $machineFactory->getMachineFactory($context)->getMachine();

echo $context->getContextState(), $cr;

foreach(['edit', 'activate', /*'deactivate', */'delete'] as $signal)
{
    echo $signal, '-------------', $cr;
    try
    {
        $machine->signal($context, $signal);
        echo $context->getContextState(), $cr;
    }
    catch(\Exception $e)
    {
        echo $e->getMessage(), $cr;
        echo $context->getContextState(), $cr;
    }
    echo '-------------', $cr, $cr;
}

