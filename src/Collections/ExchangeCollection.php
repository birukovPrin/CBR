<?php

namespace App\Collections;

use App\Models\Exchange;
use DateTime;
use Iterator;

/**
 * Class ExchangeCollection
 */
class ExchangeCollection implements Iterator
{
    /**
     * @var array
     */
    private array $exchanges = [];

    /**
     * @param Exchange $exchange
     */
    public function add(Exchange $exchange)
    {
        $this->exchanges[$exchange->getDate()->getTimestamp()] = $exchange;
    }

    /**
     * @return Exchange|false
     */
    public function current()
    {
        return current($this->exchanges);
    }

    /**
     * @return Exchange|false
     */
    public function next()
    {
        return next($this->exchanges);
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return (string) key($this->exchanges);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->exchanges) !== null;
    }


    public function rewind(): void
    {
        reset($this->exchanges);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->exchanges);
    }

    /**
     * @param DateTime $date
     * @return Exchange|null
     */
    public function getExchangeByDate(DateTime $date): ?Exchange
    {
        return $this->exchanges[$date->getTimestamp()] ?? null;
    }
}