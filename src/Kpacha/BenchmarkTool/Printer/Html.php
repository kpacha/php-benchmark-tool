<?php

namespace Kpacha\BenchmarkTool\Printer;

use Kpacha\BenchmarkTool\Container;

/**
 * Html report printer. Completes the context and delegates the rendering to the private printer
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Html implements Printer
{

    /**
     * @var Printer
     */
    private $printer;

    /**
     * @var FinderFactory
     */
    private $finderFactory;

    public function __construct(Container $container)
    {
        $this->printer = $container['twigWrapper'];
        $this->finderFactory = $container['finderFactory'];
    }

    public function render($template, $context = array())
    {
        return $this->printer->render($template, $this->prepareContext($context));
    }

    public function dump($destination, $template, $context = array())
    {
        return $this->printer->dump($destination, $template, $this->prepareContext($context));
    }

    protected function prepareContext($context)
    {
        $hashes = $this->getHashes($context['targets']);
        return array_merge(
                        $context,
                        array(
                    'groups' => array_keys($context['targets']),
                    'hashes' => $hashes,
                    'logs' => $this->getLogs($context['config']['html']['logPath'], $hashes)
                        )
        );
    }

    private function getHashes(array $targets)
    {
        $hashes = array();
        foreach ($targets as $groupName => $group) {
            foreach ($group as $target) {
                $hashes[$groupName][$target] = md5($target);
            }
        }
        return $hashes;
    }

    private function getLogs($logPath, array $hashes)
    {
        $logs = array();
        foreach ($hashes as $group) {
            foreach ($group as $hash) {
                $logs[$hash] = $this->getLog($logPath, $hash);
            }
        }
        return $logs;
    }

    private function getLog($logPath, $hash)
    {
        $finder = $this->finderFactory->create()->files()->in($logPath)->name("$hash.log");
        foreach ($finder as $file) {
            return $file;
        }
    }

}
