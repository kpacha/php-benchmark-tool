<?php

namespace Kpacha\BenchmarkTool\Printer;

/**
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
interface Printer
{

    public function render($template, $context = array());

    public function dump($destination, $template, $context = array());
}
