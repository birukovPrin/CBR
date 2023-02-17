<?php

namespace App\Services;

use Psr\Cache\CacheItemPoolInterface;
use App\Collections\CurrencyCollection;
use App\Collections\ExchangeCollection;

class CacheCollectionService
{
    public const HOUR = 3600; 

    public function __construct(
        private CacheItemPoolInterface $cachePool
    ){}    

    public function getCurrencies(string $key): ?CurrencyCollection
    {
        $value = $this->getItemValue($key);

        if ($value instanceof CurrencyCollection) {
            return $value;
        }

        return null;
    }

    public function getExchanges(string $key): ?ExchangeCollection 
    {
        $value = $this->getItemValue($key);

        if ($value instanceof ExchangeCollection) {
            return $value;
        }

        return null;
    }

    private function getItemValue(string $key): mixed
    {
        if (!$this->cachePool->hasItem($key)) {
            return null;
        }

        $item = $this->cachePool->getItem($key);
        
        return $item->get();
    }

    public function saveCurrencies(CurrencyCollection $collection, string $key): void
    {
         $this->saveItem($key, $collection, 24*self::HOUR);
    }

    public function saveExchanges(ExchangeCollection $collection, string $key): void
    {
        $this->saveItem($key, $collection, self::HOUR);
    }

    private function saveItem(string $key, mixed $value, int $expires): void
    {
        $item = $this->cachePool->getItem($key);
        
        if (!$item->isHit())
        {
            $item->set($value);
            $item->expiresAfter($expires);
            $this->cachePool->save($item);
        }
    }

}