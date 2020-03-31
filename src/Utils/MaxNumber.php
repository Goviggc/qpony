<?php

namespace App\Utils;

/**
 * Class MaxNumber
 * @package App\Utils
 */
class MaxNumber
{
    /**
     * @var int $endKey
     */
    private $endKey;

    /**
     * @var int
     */
    private static $minNumber = 0;

    /**
     * @var int
     */
    private static $maxNumber = 99999;

    /**
     * MaxNumber constructor.
     * @param null|int $number
     * @throws \Exception
     */
    public function __construct($number = null)
    {
        if(gettype($number === "integer"))
            $this->setStartNumber($number);
    }

    /**
     * @param int $number
     * @throws \Exception
     */
    public function setStartNumber(int $number): void
    {
        if($number <= $this::$minNumber || $number > $this::$maxNumber)
            throw new \Exception(sprintf("Liczby muszą zaiwerać się w przedziale od %d do %d", $this::$minNumber, $this::$maxNumber));
        $this->endKey = $number;
    }

    /**
     * @return array
     */
    public function getNumbersTable(): array
    {
        $ret = [0, 1];

        for($i = 2; $i <= $this->endKey; $i++)
            if($i % 2)
            {
                $retKey = ($i - 1) / 2;
                $ret[$i] = $ret[$retKey] + $ret[$retKey + 1];
            }
            else
            {
                $ret[$i] = $ret[$i / 2];
            }


        return $ret;
    }

    /**
     * @param int $i
     * @return int
     * @throws \Exception
     */
    public function getNumber(int $i): int
    {
        $arr = $this->getNumbersTable();

        if(!array_key_exists($i, $arr) || gettype($arr[$i]) !== "integer")
            throw new \Exception("Key does not exists or not valid type");

        return $arr[$i];
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getMaxNumber(): int
    {
        return $this->getNumber($this->endKey);
    }
}