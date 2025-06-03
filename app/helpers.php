<?php

if (! function_exists('formatNumberAbbreviated')) {
    function formatNumberAbbreviated($number)
    {
        if ($number >= 1_000_000_000) {
            return round($number / 1_000_000_000, 1) . 'B';
        } elseif ($number >= 1_000_000) {
            return round($number / 1_000_000, 1) . 'M';
        } elseif ($number >= 1_000) {
            return round($number / 1_000, 1) . 'K';
        }

        return $number;
    }
}

if (! function_exists('formatSwissNumber')) {
    function formatSwissNumber($number)
    {
        return str_replace(',', "'", number_format($number, 0, '.', ','));
    }
}
