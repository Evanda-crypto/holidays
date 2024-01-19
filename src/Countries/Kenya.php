<?php

namespace Spatie\Holidays\Countries;

use Carbon\CarbonImmutable;

class Kenya extends Country
{
    public function countryCode(): string
    {
        return 'ke';
    }

    protected function allHolidays(int $year): array
    {
        return array_merge([
            "New Year's Day" => '01-01',
            'Labour Day' => '05-01',
            'Madaraka Day' => '06-01',
            'Huduma Day' => '10-10',
            'Mashujaa Day' => '10-20',
            'Jamhuri Day' => '12-12',
            'Christmas Day' => '12-25',
            'Boxing Day' => '12-26',
        ], $this->variableHolidays($year));
    }

    /** @return array<string, CarbonImmutable> */
    protected function variableHolidays(int $year): array
    {
        $easter =  $this->getEasterDate($year);

        return [
            'Good Friday' => $this->getGoodFridayDate($easter),
            'Easter Monday' => $easter,
        ];
    }

    private function getEasterDate($year)
    {
        // Get the actual Easter Monday (Observed from Sundays)
        $a = $year % 19;
        $b = floor($year / 100);
        $c = $year % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $month = floor(($h + $l - 7 * $m + 114) / 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return CarbonImmutable::create($year, $month, $day, 0, 0, 0);
    }

    private function getGoodFridayDate(CarbonImmutable $easterDate)
    {
        // Good Friday is always two days before Easter
        return $easterDate->subDays(2);
    }
}
