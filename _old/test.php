<?php


function add_comm_days_continuity($days, $long)
    {
        if(in_array(0, $days)) return true;

        $start = null;

        for($i=1; $i<=7; $i++)
        {
            if(!in_array($i, $days))
            {
                $start = $i;
                break;
            }
        }

        if(is_null($start)) return true;

        $best = 0;
        $bbest = 0;

        for($i=0; $i<7; $i++)
        {
            $day = ($start+$i)%7 + 1;

            if(in_array($day, $days))
            {
                $best++;
            }
            else
            {
                if($best > $bbest) $bbest = $best;
                $best = 0;
            }
        }

        return $bbest >= $long;
    }

    var_dump(add_comm_days_continuity(array(1,2,4,5,6), 3));

?>
