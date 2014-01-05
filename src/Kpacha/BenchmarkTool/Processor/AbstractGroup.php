<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of AbstractGroup
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class AbstractGroup extends Gnuplot
{

    protected function buildGraphs($name, $files)
    {
        $this->exec($this->getCommandOptions($name, $files));
    }

    abstract protected function getCommandOptions($name, $files);

}

