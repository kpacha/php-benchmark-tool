<?php

namespace Kpacha\BenchmarkTool\Processor;

/**
 * Description of ResponseTimeDistribution
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class ResponseTimeDistribution extends ResponseTime
{

    protected function getCommandOptions($name, $input, $output)
    {
        $input = str_replace(self::AB_DATA_EXTENSION, self::CSV_LOG_EXTENSION, $input);
        return <<<EOD
-e "set terminal pngcairo transparent enhanced font \"arial,10\" fontscale 1.0 size 500, 350; \
    set size 1,1; set grid y; set key left top; \
    set xlabel '% of requests'; set ylabel 'ms'; \
    set autoscale fix; \
    set datafile separator ','; \
    set title \"Response time distribution\"; \
    set output '{$this->outputPath}$output.percentage.png'; \
    plot \"$input\" using 1:2 with lines title 'Response';"
EOD;
    }

}
