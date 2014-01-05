<?php

namespace Kpacha\BenchmarkTool\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * The command to rule them all
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class BenchmarkCommand extends BaseCommand
{

    const SERVICE_KEY = 'benchmark';

    protected function configure()
    {
        $this
                ->setName('benchmark:run')
                ->setDescription('Run all the component-benchmark phases')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = $this->container['commands'];
        unset($commands[self::SERVICE_KEY]);

        foreach ($commands as $serviceName) {
            $output->writeln("<info>Running the service [</info>$serviceName<info>]</info>");
            $command = $this->getApplication()->find($this->container[$serviceName]->getName());
            $returnCode = $command->run(new ArrayInput(array(null)), $output);
            if ($returnCode == 0) {
                $output->writeln("<info>Running the service [</info>$serviceName<info>]</info> - <comment>OK</comment>");
            }
        }
    }

}
