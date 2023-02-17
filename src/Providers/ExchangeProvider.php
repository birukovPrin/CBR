<?php 

namespace App\Providers;

use DateTime;
use App\Models\Currency;
use App\Collections\ExchangeCollection;
use App\Collections\CurrencyCollection;

interface ExchangeProvider 
{
      public function getRateForPeriod(DateTime $fromDate, DateTime $toDate, Currency $currency): ExchangeCollection;

      public function getAllCurrencies(): CurrencyCollection;

      public function getProviderBaseCurrency(): Currency;
}