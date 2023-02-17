<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

class RateRequestForm
{
    /**
    * @Assert\NotBlank
    * @Assert\Currency
    */
    private string $baseChar = '';

    /**
    * @Assert\NotBlank
    * @Assert\Currency
    */
    private string $currencyChar = '';

   /**
    * @Assert\NotBlank
    * @Assert\Type("DateTime")
    */
    private ?DateTime $date = null;

    public function getCurrencyChar(): string
    {
        return $this->currencyChar;
    }

    public function setCurrencyChar(string $char): RateRequestForm
    {
        $this->currencyChar = $char;

        return $this;
    }

    public function getBaseChar(): string
    {
        return $this->baseChar;
    }

    public function setBaseChar(string $char): RateRequestForm
    {
        $this->baseChar = $char;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): RateRequestForm
    {
        $this->date = $date;

        return $this;
    }

    public function getPrevDate(): DateTime
    {
        $this->prevDate = clone $this->date;

        return $this->prevDate->modify('-1 day');
    }
}