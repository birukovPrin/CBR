<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Currency
 */
final class Currency
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    private string $name = '';

    /**
     * @var int
     */
    private int $quantity = 1;

    /**
     * ISO Цифровой код валюты
     *
     * @var string
     */
    private string $numCode = '';

    /**
    * ISO Символьный код валюты
    **
    * @Assert\NotBlank
    * @Assert\Currency
    *
    * @var string
    */
    private string $charCode = '';

    /**
    * Внутренний id валюты
    * 
    * @Assert\NotBlank
    *
    * @var  string
    */
    private string $idCode = '';

    /**
    * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Currency
     */
    public function setName(string $name): Currency
    {
        $this->name = $this->cleaner($name);

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
     * @param int $quantity
     * @return Currency
     */
    public function setQuantity(int $quantity): Currency
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumCode(): string
    {
        return $this->numCode;
    }

    /**
     * @param string $numCode
     * @return Currency
     */
    public function setNumCode(string $numCode): Currency
    {
        $this->numCode = $this->cleaner($numCode);

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
     * @param string $charCode
     * @return Currency
     */
    public function setCharCode(string $charCode): Currency
    {
        $this->charCode = $this->cleaner($charCode);

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
     * @param string $idCode
     * @return Currency
     */
    public function setIdCode(string $idCode): Currency
    {
        $this->idCode = $this->cleaner($idCode);

        return $this;
    }

    /**
     * @param string $str
     * @return string
     */
    private function cleaner(string $str): string
    {
        return trim(preg_replace('/\s\s+/', ' ', $str));
    }
}