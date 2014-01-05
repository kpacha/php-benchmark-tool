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
        return <<<EOD
-e "set terminal pngcairo transparent enhanced font \"arial,10\" fontscale 1.0 size 500, 350; \
    set size 1,1; set grid y; set key left top; \
    set xlabel 'Number of requests'; set ylabel 'ms'; \
    set autoscale fix; \
    set datafile separator '\t'; \
    set title \"Number of requests with response time lower than\"; \
    set output '{$this->outputPath}$output.sequence.png'; \
    stats '$input' using 5 prefix 'A' nooutput; \
    plot \"$input\" using 5 with lines title 'Response', A_mean title 'Mean', A_median title 'Median';"
EOD;
    }

}

