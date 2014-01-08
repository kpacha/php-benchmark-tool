<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of AbstractGroup
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class AbstractGroup extends Gnuplot
{

    protected function buildGraphs($name, $targets, $files)
    {
        $this->exec($this->getCommandOptions($name, $targets, $files));
    }

    abstract protected function getCommandOptions($name, $targets, $files);

}