<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Kpacha\BenchmarkTool\Container;

/**
 * Description of GnuplotCommand
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class GnuplotCommand extends ProcessorCommand
{

    const CONFIG_KEY = 'gnuplot';

    public function __construct(Container $container, $name = null)
    {
        parent::__construct(self::CONFIG_KEY, $container, $name);
    }

    protected function configure()
    {
        $this
                ->setName('benchmark:gnuplot')
                ->setDescription('Update graphs with gnuplot')
                ->addArgument(
                        'templates', InputArgument::OPTIONAL, 'The templates to plot (comma-separated)', '*'
                )
                ->addOption(
                        'logPath', null, InputOption::VALUE_REQUIRED, 'Where are the log files?'
                )
                ->addOption(
                        'outputPath', null, InputOption::VALUE_REQUIRED, 'Where are the graph files dumped?'
                )
        ;
    }

    protected function getTemplates(InputInterface $input)
    {
        $templates = parent::getTemplates($input);
        if (!count($templates)) {
            $templates = $this->getPlotterFactory()->getTemplateNames();
        }
        return $templates;
    }

    protected function process($template)
    {
        $this->getPlotterFactory()->create($template)->process($this->getTargets(), $this->getConfig());
    }

    private function getPlotterFactory()
    {
        return $this->container['plotterFactory'];
    }

}
