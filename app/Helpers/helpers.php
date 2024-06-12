<?php

use Illuminate\Support\Carbon;

if (!function_exists('getMonthName')) {
    function getMonthName($monthNumber = null)
    {
        $monthNumber = $monthNumber ?: Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        switch ($monthNumber) {
            case 1:
                return 'January ' . $currentYear;
            case 2:
                return 'February ' . $currentYear;
            case 3:
                return 'March ' . $currentYear;
            case 4:
                return 'April ' . $currentYear;
            case 5:
                return 'May ' . $currentYear;
            case 6:
                return 'June ' . $currentYear;
            case 7:
                return 'July ' . $currentYear;
            case 8:
                return 'August ' . $currentYear;
            case 9:
                return 'September ' . $currentYear;
            case 10:
                return 'October ' . $currentYear;
            case 11:
                return 'November ' . $currentYear;
            case 12:
                return 'December ' . $currentYear;
            default:
                return 'Invalid Month ' . $currentYear;
        }
    }
}
