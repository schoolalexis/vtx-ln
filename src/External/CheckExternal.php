<?php

namespace App\External;

class Check
{
    public function isValidDate($date, $format = "m/d/Y")
    {
        $dateT = \DateTime::createFromFormat($format, $date);
        return $dateT && $dateT->format($format) == $date;
    }

    public function isAdult($data)
    {
        if(Check::isValidDate($data))
        {
            $date = new \DateTime($data);
            $today = new \DateTime('now');
            $diff = $today->diff($date);
            $age = $diff->y;
            return $age >= 18;
        }
        return false;
    }

    public function isValidSize($in, $cm)
    {
        $in = $cm/2.54;
        $feet = intval($in/12);
        $in = $in%12;
        return printf("%d"." : "."%d"." : ", $feet, $in) === $in;
    }
}