<?php

namespace Kpacha\BenchmarkTool\Helper;

use Kpacha\BenchmarkTool\Container;

/**
 * Description of GnuplotFactory
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class GnuplotFactory
{

    private $finderFactory;
    private $plotters;

    public function __construct(Container $container)
    {
        $this->finderFactory = $container['finderFactory'];
        $this->plotters = $container['plotters'];
    }

    /**
     * Just get a new plotter by template!
     * 
     * @param string $template
     * @return \Kpacha\BenchmarkTool\Processor\Gnuplot
     * @throws \InvalidArgumentException
     */
    public function create($template)
    {
        if (!isset($this->plotters[$template])) {
            throw new \InvalidArgumentException("Unknown template [$template]");
        }
        $className = $this->plotters[$template];
        return new $className($this->finderFactory);
    }

    /**
     * @return array Templates loaded
     */
    public function getTemplateNames()
    {
        return array_keys($this->plotters);
    }

}
