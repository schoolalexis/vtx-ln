<?php

namespace App\External;

use League\Csv\Reader;

class CSVExternal {
    public function getColumns(): array
    {
        return ['Gender', 'Title', 'Surname', 'GivenName', 'EmailAddress', 'Birthday', 'TelephoneNumber', 'CCType', 'CCNumber', 'CVV2', 'CCExpires', 'StreetAddress', 'City', 'ZipCode', 'CountryFull', 'Centimeters', 'Kilograms', 'Vehicle', 'Latitude', 'Longitude'];
    }

    public function getFileHeader($file): array
    {
        return Reader::createFromPath($file)->setHeaderOffset(0)->getHeader();
    }

    public function isValidFileHeader($file): bool
    {
        $file = array_map('strlower', $file);
        $fileColums = array_map('strlower', CSVExternal::getColumns());
        $i = 0;

        foreach ($file as $val) 
        {
            if (in_array($val, $fileColums))
            {
                $i++;
            }
        }
        return $i === count($fileColums);
    }

    public function getArrayForTwoFiles($file0, $file1): array
    {
        $arrayNumber = [];

        foreach ($file0 as $val)
        {
            $val = array_change_key_case($val, CASE_LOWER);
            $arrayNumber[] = $val["ccnumber"];
        }

        foreach ($file1 as $val)
        {
            $val = array_change_key_case($val, CASE_LOWER);
            $arrayNumber[] = $val["ccnumber"];
        }

        return $arrayNumber;
    }

    public function getDuplicateValArray($array): array
    {
        $iVal = [];
        $uniqueArray = array_unique($array);

        if(count($array) - count($uniqueArray))
        {
            for($i = 0; $i < count($array); $i++)
            {
                if(!array_key_exists($i, $uniqueArray))
                {
                    $iVal[] = $array[$i];
                }
            }
        }
        return array_unique($iVal);
    }

}