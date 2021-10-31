<?php

namespace App\External;

use League\Csv\AbstractCsv;
use League\Csv\Reader;
use League\Csv\Writer;

class MergeExternal
{
    private $addClient;
    private $colums;
    private $file0;
    private $file1;
    private $mergeCSV;
    private $arrayNumber;

    /**
     * Construtor
     *
     * @param $file0
     * @param $file1
     */
    public function __construct($file0, $file1)
    {
        $this->colums = array_map('strlower', CSVExternal::getColumns());
        $this->file0 = Reader::createFromPath($file0->setHeaderOffset(0));
        $this->file1 = Reader::createFromPath($file1->setHeaderOffset(0));
        $this->mergeCSV = Writer::createFromFileObject(new \SplTempFileObject());
        $this->mergeCSV->insertOne(CSVExternal::getColumns());
        $this->addClient = 0;
        $this->arrayNumber = CSVExternal::getArrayForTwoFiles($this->file0->getRecords(), $this->file1->getRecords());

    }

    public function processSequential($data)
    {
        foreach ($data as $val)
        {
            $val = array_change_key_case($val, CASE_LOWER);
            $content = [];
            foreach ($this->colums as $column)
            {
                $content[] = $val[$column];
            }

            if(Check::isAdult($val["birthday"]) && Check::isValidSize($val["feetinches"], $val["centimeters"]) 
                && !in_array($val["ccnumber"], CSVExternal::getDuplicateValArray($this->arrayNumber)))
                {
                    $this->mergeCSV->insertOne($content);
                    $this->addClient = 1;
                }
        }
    }

    public function sequential()
    {
        $this->processSequential($this->file0->getRecords());
        $this->processSequential($this->file1->getRecords());
        
        return $this->addClient === 1;
    }

    public function ddl()
    {
        return $this->mergeCSV->output($this->type.date('Y-m-d')."csv");
    }

    public function getColumns()
    {
        return $this->colums;
    }

    public function getFile0()
    {
        return $this->file0;
    }

    public function getFile1()
    {
        return $this->file1;
    }

}