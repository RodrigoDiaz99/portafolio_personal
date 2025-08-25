<?php

if (! function_exists('formatCount')) {
    function formatCount(int $n, int $precision = 1): string
    {
        if ($n < 1000) {
            return (string) $n;
        }
        $units = ['', 'K', 'M', 'B', 'T'];
        $power = floor(log($n, 1000));
        $value = $n / (1000 ** $power);
        return round($value, $precision) . $units[$power];
    }
}