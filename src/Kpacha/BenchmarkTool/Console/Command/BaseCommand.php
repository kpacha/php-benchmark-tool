<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Kpacha\BenchmarkTool\Container;

/**
 * Description of Command
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class BaseCommand extends Command
{

    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container, $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function getTargets()
    {
        return $this->container['targets'];
    }

    protected function getConfig($key = null)
    {
        return ($key) ? $this->container[$key] : $this->container;
    }

    protected function setConfig($key, $value)
    {
        return $this->container[$key] = $value;
    }

    protected function updateConfig(InputInterface $input, $optionNames)
    {
        foreach ((array) $optionNames as $optionName) {
            if ($optionValue = $input->getOption($optionName)) {
                $this->setConfig($optionName, $optionValue);
            }
        }
    }

}
