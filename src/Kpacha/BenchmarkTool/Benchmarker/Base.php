<?php

namespace Kpacha\BenchmarkTool\Benchmarker;

/**
 * The base benchmarker
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Base
{

    /**
     * @var string
     */
    private $commandPath;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var string
     */
    private $output;

    /**
     * Default constructor for the command-related classes.
     * 
     * @param string $commandPath The path to the binary
     * @param array $arguments The arguments passed to the command
     */
    public function __construct($commandPath, $arguments = array())
    {
        $this->commandPath = $commandPath;
        $this->arguments = (array) $arguments;
    }

    /**
     * Run the command with the given target
     * 
     * @param string $target
     */
    public function run($target)
    {
        $return = $this->exec($this->getCommand($target));
        return $this->output[$target] = $this->cleanOutput($target, $return['output']);
    }

    /**
     * Get the processed output
     * 
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function getName()
    {
        return __CLASS__;
    }
    
    public function getArguments()
    {
        return $this->arguments;
    }

    protected function getCommand($target)
    {
        return trim($this->commandPath . ' ' . $this->getParsedArguments($target));
    }

    protected function exec($shellCommand)
    {
        $return = array('output' => array(), 'status' => null);
        exec($shellCommand, $return['output'], $return['status']);
        return $return;
    }

    protected function cleanOutput($target, array $output)
    {
        return trim(implode("\n", $output));
    }

    protected function getParsedArguments($target)
    {
        return trim(implode(' ', $this->arguments) . ' ' . $target);
    }

    protected function getArgument($name, $default)
    {
        return (isset($this->arguments[$name])) ? $this->arguments[$name] : $default;
    }

}
