<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Kpacha\BenchmarkTool\Container;

/**
 * Description of AbCommand
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class AbCommand extends BaseCommand
{

    public function __construct(Container $container, $name = null)
    {
        parent::__construct($container, $name);
    }

    protected function configure()
    {
        $this
                ->setName('benchmark:ab')
                ->setDescription('Launch the ab test phase')
                ->addArgument(
                        'url', InputArgument::OPTIONAL, 'The urls to test (comma-separated)', '*'
                )
                ->addOption(
                        'logPath', null, InputOption::VALUE_REQUIRED, 'Where are the log files?'
                )
                ->addOption(
                        'timeLimit', 't', InputOption::VALUE_REQUIRED, 'Total time of the benchmark'
                )
                ->addOption(
                        'concurrency', 'c', InputOption::VALUE_REQUIRED, 'Number of concurrent requests'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateConfig($input, array('logPath', 'timeLimit', 'concurrency'));
        $urls = $this->getUrls($input);
        $total = 0;
        foreach ($urls as $groupName => $group) {
            if (!is_int($groupName)) {
                $output->writeln("Benchmarking the group [<info>$groupName</info>] ");
            }
            foreach ((array) $group as $url) {
                $output->write("Benchmarking the url [<info>$url</info>]");
                $output->writeln(' Done. <comment>(' . $this->process($url) . ' req/s)</comment>');
                $total++;
            }
        }
        $output->writeln('Done. <comment>' . $total . '</comment> urls have been benchmarked');
    }

    protected function process($url)
    {
        \sleep($this->container['global']['wait']);
        return $this->getBenchmarker()->run($url);
    }

    protected function getUrls(InputInterface $input)
    {
        $urls = array_diff(explode(',', $input->getArgument('url')), array('*'));
        if (!count($urls)) {
            $urls = $this->container['targets'];
        }
        return $urls;
    }

    protected function getConfig($key = null)
    {
        return ($key) ? $this->container['ab']['arguments'][$key] : $this->container['ab']['arguments'];
    }

    protected function setConfig($key, $value)
    {
        $config = $this->container['ab'];
        $config['arguments'] = array_merge($config['arguments'], array($key => $value));
        return $this->container['ab'] = $config;
    }

    private function getBenchmarker()
    {
        return $this->container[$this->container['benchmarkers']['ab']];
    }

}
