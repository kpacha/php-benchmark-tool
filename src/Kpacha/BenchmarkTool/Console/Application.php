<?php

namespace Kpacha\BenchmarkTool\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Kpacha\BenchmarkTool\Container;
 
/**
 * The Component-Benchmark Console Application
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Application extends BaseApplication
{
    const NAME = 'The "Component-Benchmark" Console Application';
    const VERSION = '0.1';
 
    public function __construct(Container $container)
    {
        parent::__construct(static::NAME, static::VERSION);
        foreach ($container['commands'] as $command){
            $this->add($container[$command]);
        }
    }
}
