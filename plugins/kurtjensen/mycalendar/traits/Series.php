<?php namespace KurtJensen\MyCalendar\Traits;

use Carbon\Carbon;

trait Series
{

    public function seriesRule($pattern, $start_at, $end_at)
    {
        $date = [];
        //'INTERVALS=1,2,1,4;COUNT=20;'
        if (is_string($pattern)) {
            $rulePieces = explode(';', $pattern);
        }

        foreach ($rulePieces as $piece) {
            if (strpos($piece, '=')) {
                list($item, $value) = explode('=', trim($piece));
                $$item = $value;
            }
        }

        if (isset($UNTIL)) {
            $until = new Carbon($UNTIL);
        } else {
            $until = (new Carbon('now'))->addYear(1);
        }
        if (isset($COUNT)) {
            $maxCount = $COUNT < 150 ? $COUNT - 1 : 149;
        } else {
            $maxCount = 150;
        }

        $on = true;
        $spointer = $start_at;
        $epointer = $end_at;
        $totalCount = 0;
        while ($totalCount < $maxCount) {
            foreach (explode(',', $INTERVALS) as $occ) {
                if ($on) {
                    // on
                    $countOn = 0;
                    while (($countOn < $occ) && ($totalCount < $maxCount)) {
                        if ($spointer->gt($until)) {
                            return $date;
                        }
                        $date[$totalCount++] = new \Recurr\Recurrence($spointer->copy(), $epointer->copy());
                        //['s' => $spointer->format('Y-m-d H:i:s'), 'e' => $epointer->format('Y-m-d H:i:s')];

                        $spointer->addDay(1);
                        $epointer->addDay(1);
                        ++$countOn;
                    }
                    $on = false;
                } else {
                    // off
                    $spointer->addDay($occ);
                    $epointer->addDay($occ);
                    $on = true;
                }
            }
        }
        return $date;
    }
}
