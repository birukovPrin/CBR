<?php

namespace App\Services;

use App\Providers\ExchangeProvider;
use App\Services\CacheCollectionService;
use App\Collections\CurrencyCollection;
use App\Collections\ExchangeCollection;
use App\Models\Currency;
use App\Models\Exchange;
use App\Exceptions\ExceptionInvalidParameter;
use DateTime;

class ExchangeService
{
    public function __construct(
        private ExchangeProvider $provider,
        private CacheCollectionService $cacheService
    ){}    

    public function getCurrencies(): CurrencyCollection
    {
        $cacheKey = $this->provider::class.__FUNCTION__;
        $currencies = $this->cacheService->getCurrencies($cacheKey);

        if ($currencies) {
            return $currencies;
        }

        $currencies = $this->provider->getAllCurrencies();
        $this->cacheService->saveCurrencies($currencies, $cacheKey);
      
        return $currencies;
    }

    private function getExchanges(DateTime $fromDate, DateTime $toDate, Currency $currency): ExchangeCollection
    {
        $cacheKey = $this->provider::class;
        $cacheKey .= $fromDate->format('Ymd').$toDate->format('Ymd');
        $cacheKey .= $currency->getCharCode();

        $exchanges = $this->cacheService->getExchanges($cacheKey);
        
        if ($exchanges) {
            return $exchanges;
        }

        $exchanges = $this->provider->getRateForPeriod($fromDate, $toDate, $currency);
        $this->cacheService->saveExchanges($exchanges, $cacheKey);

        return $exchanges;
    }

    public function getDynamicRate(
        string $targetCharCode,
        string $baseCharCode,
        DateTime $fromDate,
        DateTime $toDate
    ): array
    {
        if ($targetCharCode == $baseCharCode) {
            return [1];
        }

        $currencies = $this->getCurrencies();
        $targetCurrency = $currencies->getCurrencyByCharCode($targetCharCode);
        $baseCurrency = $currencies->getCurrencyByCharCode($baseCharCode);

        if (!$baseCurrency || !$targetCurrency) {
            throw new ExceptionInvalidParameter('Currencies not found.');
        }

        $providerBase = $this->provider->getProviderBaseCurrency();

        if ($baseCurrency == $providerBase) {
            return $this->getDynamicRateByProviderBase($targetCurrency, $fromDate, $toDate);
        }

        if ($targetCurrency == $providerBase) {
            return $this->getDynamicProviderBaseRateByCurrency($baseCurrency, $fromDate, $toDate);
        }

        return $this->getDynamicRateByCurrency($targetCurrency, $baseCurrency, $fromDate, $toDate);
    }

    protected function getDynamicRateByProviderBase(
        Currency $targetCurrency,   
        DateTime $fromDate,
        DateTime $toDate
    ): array
    {
        $exchanges = $this->getExchanges($fromDate, $toDate, $targetCurrency);
        $result = [];
        foreach ($exchanges as $exchange) {
            $rateBase = 1/$this->provider->getProviderBaseCurrency()->getQuantity();
            $result[$exchange->getDate()->format('Y.m.d')] = round($exchange->getRate()/$rateBase, 4);
        }

        return $result;
    }

    protected function getDynamicProviderBaseRateByCurrency(
        Currency $targetCurrency,   
        DateTime $fromDate,
        DateTime $toDate
    ): array
    {
        $exchanges = $this->getExchanges($fromDate, $toDate, $targetCurrency);
        $result = [];
        foreach ($exchanges as $exchange) {
            $rate = 1/$this->provider->getProviderBaseCurrency()->getQuantity();
            $result[$exchange->getDate()->format('Y.m.d')] = round($rate/$exchange->getRate(), 4);
        }

        return $result;
    }

    protected function getDynamicRateByCurrency(
        Currency $targetCurrency,
        Currency $baseCurrency,
        DateTime $fromDate,
        DateTime $toDate
    ): array
    {
        $exchangesTarget = $this->getExchanges($fromDate, $toDate, $targetCurrency);
        $exchangesBase = $this->getExchanges($fromDate, $toDate, $baseCurrency);

        $result = [];
        foreach ($exchangesTarget as $exchangeTarget) {
            $exchangeBase = $exchangesBase->getExchangeByDate($exchangeTarget->getDate());
            $result[$exchangeTarget->getDate()->format('Y.m.d')] = round($exchangeTarget->getRate()/$exchangeBase->getRate(), 4);
        }

        return $result;
    }
}