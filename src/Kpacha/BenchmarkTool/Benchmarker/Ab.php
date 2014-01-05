<?php

namespace Kpacha\BenchmarkTool\Benchmarker;

use Kpacha\BenchmarkTool\Benchmarker\Base;

/**
 * The Ab benchmarker
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Ab extends Base
{

    const CONCURRENCY = 5;
    const TIME_LIMIT = 10;
    const LOG_PATH = 'ab/';

    public function getName()
    {
        return __CLASS__;
    }

    protected function getParsedArguments($target)
    {
        $this->notEmpty($target, 'A target is required!');
        $timeLimit = $this->getArgument('timeLimit', self::TIME_LIMIT);
        $concurrency = $this->getArgument('concurrency', self::CONCURRENCY);
        $logFile = $this->getLogFile($target);
        $plotableLog = $this->getPlotableLogFile($target);
        return "-k -t $timeLimit -c $concurrency -g $plotableLog $target > $logFile 2> /dev/null";
    }

    protected function cleanOutput($target, array $output)
    {
        preg_match('/Requests per second: *(.*)/', $this->getLogFileContents($target), $matches);
        return (isset($matches[1])) ? (float) $matches[1] : null;
    }

    protected function getPlotableLogFile($target)
    {
        return str_replace('.log', '.dat', $this->getLogFile($target));
    }

    protected function getLogFile($target)
    {
        return $this->getLogPath() . md5($target) . '.log';
    }

    protected function getLogPath()
    {
        return $this->getArgument('logPath', self::LOG_PATH);
    }

    protected function getLogFileContents($target)
    {
        $text = $this->getFileContents($this->getLogFile($target));
        $this->notEmpty($text, 'Empty log file!');
        return $text;
    }

    protected function getFileContents($filePath)
    {
        return @file_get_contents($filePath);
    }
    
    private function notEmpty($subject, $message)
    {
        if (empty($subject)) {
            throw new \Exception($message);
        }
    }

}
