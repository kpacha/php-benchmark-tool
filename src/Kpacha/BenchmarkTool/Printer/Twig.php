<?php

namespace Kpacha\BenchmarkTool\Printer;

use \Twig_Loader_Filesystem;
use \Twig_Environment;

/**
 * Simple wrapper for the Twig component
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Twig
{

    const TEMPLATE_EXTENSION = '.twig';

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct($templatePath)
    {
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem($templatePath));
    }

    public function render($template, $context = array())
    {
        return $this->twig->render($template . self::TEMPLATE_EXTENSION, $context);
    }

    public function dump($destination, $template, $context = array())
    {
        file_put_contents($destination, $this->render($template, $context));
    }

}
