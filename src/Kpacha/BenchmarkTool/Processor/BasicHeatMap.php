<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of BasicHeatMap
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class BasicHeatMap extends AbstractGroup
{

    protected function getCommandOptions($name, $targets, $files)
    {
        $plot = array();
        foreach ($files as $key => $file) {
            $plot[] = sprintf(
                    "'%s' using 2:5 title '%s' with points pt 1 ps 0.5", $file, basename($targets[$key], '.php')
            );
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
