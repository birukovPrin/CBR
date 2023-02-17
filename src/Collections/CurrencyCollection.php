<?php

namespace App\Collections;

use App\Models\Currency;
use Iterator;

/**
 * Class CurrencyCollection
 */
class CurrencyCollection implements Iterator
{
    /**
     * @var array
     */
    private $currencies = [];

    /**
     * @param Currency $currency
     */
    public function add(Currency $currency)
    {
        $this->currencies[$currency->getCharCode()] = $currency;
    }

    /**
     * @return Currency|false
     */
    public function current()
    {
        return current($this->currencies);
    }

    /**
     * @return Currency|false
     */
    public function next()
    {
        return next($this->currencies);
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return (string) key($this->currencies);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->currencies) !== null;
    }


    public function rewind(): void
    {
        reset($this->currencies);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->currencies);
    }

    /**
     * @param string $charCode
     * @return Currency|null
     */
    public function getCurrencyByCharCode(string $charCode): ?Currency
    {
        return $this->currencies[$charCode] ?? null;
    }
}