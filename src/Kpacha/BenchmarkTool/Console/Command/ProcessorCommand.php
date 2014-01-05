<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kpacha\BenchmarkTool\Container;

/**
 * Description of ProcessorCommand
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class ProcessorCommand extends BaseCommand
{

    /**
     * @var string
     */
    private $configKey;

    public function __construct($configKey, Container $container, $name = null)
    {
        parent::__construct($container, $name);
        $this->configKey = $configKey;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateConfig($input, array('logPath', 'outputPath'));
        $templates = $this->getTemplates($input);
        foreach ($templates as $template) {
            $output->writeln("Processing the template [<info>$template</info>]");
            $this->process($template);
        }
        $output->writeln('Done. <comment>' . count($templates) . '</comment> templates have been processed');
    }

    abstract protected function process($template);

    protected function getConfig($key = null)
    {
        return ($key) ? $this->container[$this->configKey][$key] : $this->container[$this->configKey];
    }

    protected function setConfig($key, $value)
    {
        return $this->container[$this->configKey][$key] = $value;
    }

    protected function getTemplates(InputInterface $input)
    {
        return array_diff(explode(',', $input->getArgument('templates')), array('*'));
    }

}
