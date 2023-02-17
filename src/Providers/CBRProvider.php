<?php

namespace App\Providers;

use DateTime;
use DateTimeInterface;
use App\Providers\ExchangeProvider;
use App\Collections\ExchangeCollection;
use App\Collections\CurrencyCollection;
use App\Models\Exchange;
use App\Models\Currency;
use App\Client\CBRClient;
use App\Client\CBR;
use App\Exceptions\ExceptionInvalidParameter;
use App\Exceptions\ExceptionIncorrectData;
use SimpleXMLElement;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CBRProvider implements ExchangeProvider
{

    public function __construct(private CBRClient $CBRClient, private ValidatorInterface $validator)
    {}

    public function getRateForPeriod(DateTime $fromDate, DateTime $toDate, Currency $currency): ExchangeCollection
    {
        $res = $this->CBRClient->getDynamicRate($fromDate,$toDate,$currency->getIdCode());
        $xml = new SimpleXMLElement($res->GetCursDynamicResult->any);
        $rates = $xml?->ValuteData?->ValuteCursDynamic;

        if (!$rates) {
            throw new ExceptionIncorrectData('No data for that day for '.$currency->getName());
        }
      
        $exchangeCollection = new ExchangeCollection();
        foreach ($rates as $rate) {
            $exchange = $this->setExchange($rate);

            $errors = $this->validator->validate($exchange);
            if (count($errors) > 0) {
                continue;
            }

            $exchangeCollection->add($exchange);
        }

        return $exchangeCollection;
    }

    private function setExchange(SimpleXMLElement $rate): Exchange 
    {
        $exchange = new Exchange();
        $date = DateTime::createFromFormat(DateTimeInterface::ISO8601, (string)$rate->CursDate);
        $exchange
            ->setDate($date)
            ->setRate((float) ($rate->Vcurs ?? 0))
            ->setIdCode((string) ($rate->Vcode ?? ''))
            ->setQuantity((int) ($rate->Vnom ?? 1));

        return $exchange;
    }

    public function getAllCurrencies(): CurrencyCollection
    {
        $result = $this->CBRClient->getCurrencyCodesDaily();

        $xml = new SimpleXMLElement($result->EnumValutesResult->any);
        $currencyElements = $xml?->ValuteData?->EnumValutes;
        
        if (!$currencyElements) {
            throw new ExceptionIncorrectData('Invalid data in the currency response.');
        }

        $currencyCollection = new CurrencyCollection();
        foreach($currencyElements as $currencyData)
        {   
            $currency = $this->setCurrency($currencyData);

            $errors = $this->validator->validate($currency);
            if (count($errors) > 0) {
                continue;
            }

            $currencyCollection->add($currency);
        }
        $currencyCollection->add($this->getProviderBaseCurrency());

        return $currencyCollection;
    }

    private function setCurrency(SimpleXMLElement $currencyData): Currency
    {
        $currency = new Currency();
        $currency
            ->setName((string) ($currencyData->VEngname ?? ''))
            ->setCharCode((string) ($currencyData->VcharCode ?? ''))
            ->setIdCode((string) ($currencyData->Vcode ?? ''))
            ->setNumCode((string) ($currencyData->VnumCode ?? ''))
            ->setQuantity((int) ($currencyData->Vnom ?? 1));

        return $currency;
    }

    public function getProviderBaseCurrency(): Currency
    {
        return (new Currency())
            ->setName(CBR::NAME_RUB)
            ->setQuantity(1)
            ->setNumCode(CBR::NUMERIC_CODE_RUB)
            ->setCharCode(CBR::SYMBOL_CODE_RUB);
    }
}