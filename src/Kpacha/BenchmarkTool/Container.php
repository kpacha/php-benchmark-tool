<?php

namespace Kpacha\BenchmarkTool;

use \Pimple;
use Kpacha\BenchmarkTool\Benchmarker\Ab;
use Kpacha\BenchmarkTool\Console\Command\AbCommand;
use Kpacha\BenchmarkTool\Console\Command\BenchmarkCommand;
use Kpacha\BenchmarkTool\Console\Command\GnuplotCommand;
use Kpacha\BenchmarkTool\Console\Command\HtmlReport;
use Kpacha\BenchmarkTool\Helper\FinderFactory;
use Kpacha\BenchmarkTool\Helper\GnuplotFactory;
use Kpacha\BenchmarkTool\Printer\Twig;
use Kpacha\BenchmarkTool\Printer\Html;

/**
 * The Container for the Component Benchmark
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Container extends Pimple
{

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->setUpServices();
    }

    private function setUpServices()
    {
        $this['finderFactory'] = $this->share(
                function () {
                    return new FinderFactory;
                }
        );

        $this->setUpBenchmarkers();
        $this->setUpPrinters();
        $this->setUpPlotters();
        $this->setUpCommands();
    }

    private function setUpBenchmarkers()
    {
        $this['benchmarkers-ab'] = $this->share(
                function ($c) {
                    return new Ab($c['ab']['path'], $c['ab']['arguments']);
                }
        );
        $this['benchmarkers'] = function ($c) {
                    return array('ab' => 'benchmarkers-ab');
                };
    }

    private function setUpPrinters()
    {
        $this['twigWrapper'] = function ($c) {
                    return new Twig($c['html']['templatePath']);
                };
        $this['htmlPrinter'] = function ($c) {
                    return new Html($c);
                };
    }

    private function setUpPlotters()
    {
        $this['plotters'] = array(
            'frequency' => 'Kpacha\BenchmarkTool\Processor\Frequency',
            'heatmap' => 'Kpacha\BenchmarkTool\Processor\BasicHeatMap',
            'response' => 'Kpacha\BenchmarkTool\Processor\ResponseTime',
            'distribution' => 'Kpacha\BenchmarkTool\Processor\ResponseTimeDistribution',
            'percentage' => 'Kpacha\BenchmarkTool\Processor\ResponseTimeDistributionPercentage',
        );
        $this['plotterFactory'] = function ($c) {
                    return new GnuplotFactory($c);
                };
    }

    private function setUpCommands()
    {
        $this['commands-ab'] = function ($c) {
                    return new AbCommand($c);
                };
        $this['commands-gnuplot'] = function ($c) {
                    return new GnuplotCommand($c);
                };
        $this['commands-htmlReport'] = function ($c) {
                    return new HtmlReport($c);
                };
        $this['commands-benchmark'] = function ($c) {
                    return new BenchmarkCommand($c);
                };
        $this['commands'] = array(
            'ab' => 'commands-ab',
            'gnuplot' => 'commands-gnuplot',
            'htmlReport' => 'commands-htmlReport',
            'benchmark' => 'commands-benchmark'
        );
    }

}
