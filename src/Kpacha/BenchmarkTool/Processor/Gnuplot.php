<?php

namespace Kpacha\BenchmarkTool\Processor;

use Kpacha\BenchmarkTool\Helper\FinderFactory;

/**
 * Gnuplot encapsulation
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class Gnuplot
{

    /**
     * @var string
     */
    private $logFolder;

    /**
     * @var string
     */
    protected $outputPath;

    /**
     * @var string
     */
    protected $commandPath;

    /**
     * @var FinderFactory
     */
    private $finderFactory;

    public function __construct(FinderFactory $finderFactory)
    {
        $this->finderFactory = $finderFactory;
    }

    public function process(array $targets, $params = array())
    {
        if (count($params)) {
            $this->setParams($params);
        }
        foreach ($targets as $groupName => $group) {
            $this->buildGraphs($groupName, $this->getDatFiles($group));
        }
    }

    public function setParams(array $params)
    {
        $this->logFolder = rtrim($params['logPath'], '/') . '/';
        $this->outputPath = rtrim($params['outputPath'], '/') . '/';
        $this->commandPath = $params['path'];
    }

    protected function getDatFiles($targets)
    {
        $pattern = array();
        foreach ($targets as $target) {
            $pattern[] = md5($target);
        }
        $finder = $this->finderFactory->create()->files()
                ->in($this->logFolder)
                ->name('@(' . implode('|', $pattern) . ')\.dat@');
        $files = array();
        foreach ($finder as $file) {
            $files[] = $file->getRealpath();
        }
        return $files;
    }

    abstract protected function buildGraphs($name, $files);

    protected function exec($commandOptions)
    {
        $return = array('output' => array(), 'status' => null);
        exec($this->commandPath . ' ' . $commandOptions, $return['output'], $return['status']);
        return $return;
    }

}

