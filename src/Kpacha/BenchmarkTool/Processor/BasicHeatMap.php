<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of BasicHeatMap
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class BasicHeatMap extends Gnuplot
{

    protected function buildGraphs($name, $files)
    {
        $this->exec($this->getCommandOptions($name, $files));
    }

    protected function getCommandOptions($name, $files)
    {
        $plot = array();
        foreach ($files as $file) {
            $plot[] = "'$file' using 2:5 title '' with points lt 1";
        }
        $plotCommand = implode(', ', $plot);
        return <<<EOD
-e "set terminal pngcairo transparent enhanced font \"arial,10\" fontscale 1.0 size 500, 350; \
    set size 1,1; set grid y; set key left top; \
    set xlabel 'time'; set ylabel 'ms'; \
    set xdata time; set timefmt \"%s\"; set datafile separator '\t'; set format x \"%S\"; \
    set title \"Response time\"; \
    set output '{$this->outputPath}$name.heatmap.png'; \
    plot $plotCommand;"
EOD;
    }

}
