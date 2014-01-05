<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Kpacha\BenchmarkTool\Container;

/**
 * HtmlReport command
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class HtmlReport extends ProcessorCommand
{

    const CONFIG_KEY = 'html';

    public function __construct(Container $container, $name = null)
    {
        parent::__construct(self::CONFIG_KEY, $container, $name);
    }

    protected function configure()
    {
        $this
                ->setName('benchmark:html')
                ->setDescription('Print the html report')
                ->addArgument(
                        'templates', InputArgument::OPTIONAL, 'The templates to render (comma-separated)', '*'
                )
                ->addOption(
                        'logPath', null, InputOption::VALUE_REQUIRED, 'Where are the log files?'
                )
                ->addOption(
                        'outputPath', null, InputOption::VALUE_REQUIRED, 'Where are the report files dumped?'
                )
        ;
    }

    protected function process($template)
    {
        $templateName = str_replace('.twig', '', $template->getBasename());
        $this->getPrinter()->dump($this->getOutputPath($templateName), $templateName, $this->getContext());
    }

    protected function getTemplates(InputInterface $input)
    {
        $templates = parent::getTemplates($input);
        if (!count($templates)) {
            $templates = $this->getAllTemplates();
        }
        return $templates;
    }
    
    private function getOutputPath($template)
    {
        return $this->getConfig('outputPath') . '/' . $template;
    }

    private function getContext()
    {
        return array(
            'targets' => $this->getTargets(),
            'config' => $this->container
        );
    }

    private function getAllTemplates()
    {
        return $this->container['finderFactory']->create()->files()->in($this->container['html']['templatePath']);
    }

    private function getPrinter()
    {
        return $this->container['htmlPrinter'];
    }

}
