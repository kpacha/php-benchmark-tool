<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of Frequency
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Frequency extends AbstractGroup
{

    protected function getCommandOptions($name, $files)
    {
        $plot = array();
        foreach ($files as $key => $file) {
            $file = str_replace(self::AB_DATA_EXTENSION, self::CSV_LOG_EXTENSION, $file);
            $plot[] = "'$file' using 1:2 smooth sbezier with lines title '$key'";
        }
        $plotCommand = implode(', ', $plot);
        return <<<EOD
-e "set terminal pngcairo transparent enhanced font \"arial,10\" fontscale 1.0 size 500, 350; \
    set size 1,1; set grid y; set key left top; \
    set xlabel '% request'; set ylabel 'ms'; set datafile separator ','; \
    set autoscale fix; \
    set title \"Response time distribution\"; \
    set output '{$this->outputPath}$name.frequency.png'; \
    plot $plotCommand;"
EOD;
    }

}

