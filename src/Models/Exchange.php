<?php

namespace App\Models;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

final class Exchange
{
    /**
    * @Assert\NotBlank
    *
    * @var float
    */
    private float $rate = 1;

    /**
    * @Assert\NotBlank
    * @Assert\Type("DateTime")
    *
    * @var DateTime 
    */
    private DateTime $date;

    /**
     * Количество (номинал)
     *
     * @Assert\Positive
     * 
     * @var int
     */
    private int $quantity = 1;

    /**
    * ISO Символьный код валюты
    *
    * @Assert\Currency
    *
    * @var string
    */
    private string $charCode = '';

    /**
     * id код валюты
     *
     * @var string
     */
    private string $idCode = '';

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate/$this->quantity;
    }

    /**
     * Курс валюты
     *
     * @param float $rate
     * @return Exchange
     */
    public function setRate(float $rate): Exchange
    {
        $this->rate = $rate;

        return $this;
    }


    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Курс валюты
     *
     * @param DateTime $date
     * @return Exchange
     */
    public function setDate(DateTime $date): Exchange
    {
        $this->date = $date;

        return $this;
    }


    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Количество (номинал)
     *
     * @param int $quantity
     * @return Exchange
     */
    public function setQuantity(int $quantity): Exchange
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdCode(): string
    {
        return $this->idCode;
    }

    /**
     * Внутренний код валюты
     *
     * @param string $idCode
     * @return Exchange
     */
    public function setIdCode(string $idCode): Exchange
    {
        $this->idCode = $this->cleaner($idCode);

        return $this;
    }

    /**
     * @return string
     */
    public function getCharCode(): string
    {
        return $this->charCode;
    }

    /**
     * ISO Символьный код валюты
     *
     * @param string $charCode
     * @return Exchange
     */
    public function setCharCode(string $charCode): Exchange
    {
        $this->charCode = $this->cleaner($charCode);
        
        return $this;
    }

    /**
     * @param string $str
     * @return string
     */
    private function cleaner(string $str)
    {
        return trim(preg_replace('/\s\s+/', ' ', $str));
    }

}