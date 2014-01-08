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

    const CSV_LOG_EXTENSION = '.csv';
    const AB_DATA_EXTENSION = '.dat';

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
            $indexedTargets = $this->getLogReferences($group);
            $this->buildGraphs($groupName, $indexedTargets, $this->getDatFiles($indexedTargets));
        }
    }

    public function setParams(array $params)
    {
        $this->logFolder = rtrim($params['logPath'], '/') . '/';
        $this->outputPath = rtrim($params['outputPath'], '/') . '/';
        $this->commandPath = $params['path'];
    }

    protected function getLogReferences($targets)
    {
        $indexedTargets = array();
        foreach ($targets as $target) {
            $indexedTargets[md5($target)] = $target;
        }
        return $indexedTargets;
    }

    protected function getDatFiles(array $targets)
    {
        $pattern = '@(' . implode('|', array_keys($targets)) . ')\\' . self::AB_DATA_EXTENSION . '@';
        $finder = $this->finderFactory->create()->files()->in($this->logFolder)->name($pattern);
        $files = array();
        foreach ($finder as $file) {
            $files[$file->getBasename(self::AB_DATA_EXTENSION)] = $file->getRealpath();
        }
        return $files;
    }

    abstract protected function buildGraphs($name, $targets, $files);

    protected function exec($commandOptions)
    {
        $return = array('output' => array(), 'status' => null);
        exec($this->commandPath . ' ' . $commandOptions, $return['output'], $return['status']);
        return $return;
    }

}

