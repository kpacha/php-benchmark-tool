<?php

namespace Kpacha\BenchmarkTool\Helper;

use Symfony\Component\Finder\Finder;

/**
 * Description of FinderFactory
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class FinderFactory
{

    /**
     * Just get a new Finder!
     * @return \Symfony\Component\Finder\Finder
     */
    public function create()
    {
        return new Finder;
    }

}
