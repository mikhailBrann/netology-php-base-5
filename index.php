<?php
function generateSchedule(int $year, int $month, int $numMonths = 1): void {
    $currentDate = new DateTime("$year-$month-01");
    
    for ($m = 0; $m < $numMonths; $m++) {
        $monthName = $currentDate->format('F Y');
        $daysInMonth = $currentDate->format('t');
        $workDay = true;
        $daysOff = 0;
        $result = [];

        echo "\n" . strtoupper($monthName) . "\n";
        echo str_repeat('=', 20) . "\n";
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = new DateTime($currentDate->format('Y-m-') . $day);
            $dayOfWeek = $date->format('N');
            $isWorkDay = false;

            switch(true) {
                case $workDay:
                    if ($dayOfWeek >= 6) {
                        while ($dayOfWeek >= 6) {
                            $day++;
                            $date->modify('+1 day');
                            $dayOfWeek = (int)$date->format('N');
                        }
                    }
    
                    $isWorkDay = true;
                    $workDay = false;
                    $daysOff = 0;
                case !$workDay:
                    $daysOff++;
                default:
                    if ($daysOff >= 2) {
                        $workDay = true;
                    }
                    break;
            }

            $printDay = $isWorkDay ? "\033[32m$day\033[0m \n" : "\033[31m$day\033[0m \n";
            $result[] = $printDay;
        }

        foreach ($result as $day) {
            echo $day;
        }
        
        $currentDate->modify('first day of next month');
    }
}


if ($argc > 2) {
    $year = (int)$argv[1];
    $month = (int)$argv[2];
    $numMonths = $argc > 3 ? (int)$argv[3] : 1;
    generateSchedule($year, $month, $numMonths);
} else {
    generateSchedule(date('Y'), date('m'));
}