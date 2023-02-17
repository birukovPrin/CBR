<?php

namespace App\Client;

use DateTime;
use SoapClient;
use SoapFault;

/**
 * Class CBRClient
 */
final class CBRClient
{
    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * @var array|string[]
     */
    private $options = [
        'version' => 'SOAP_1_2'
    ];

    /**
     * CBRClient constructor.
     * 
     * @throws SoapFault
     */
    public function __construct()
    {
        $this->createSoapClient();
    }

    /**
     * Динамика курса по датам
     *
     * @return mixed
     */
    public function getDynamicRate(DateTime $fromDate, DateTime $toDate, string $idCode)
    {
        $method = CBR::METHOD_GET_DYNAMIC_RATE;

        return $this->soapClient->$method([
            'FromDate' => $fromDate->format('Y-m-d'),
            'ToDate' => $toDate->format('Y-m-d'),
            'ValutaCode' => $idCode
        ]);
    }

    /**
     * Перечень ежедневных валют
     *
     * @return mixed
     */
    public function getCurrencyCodesDaily()
    {
        return $this->getCurrencyCodes(false);
    }

    /**
     * Перечень ежемесячных валют
     *
     * @return mixed
     */
    public function getCurrencyCodesMonthly()
    {
        return $this->getCurrencyCodes(true);
    }

    /**
     * @param bool $type
     * @return mixed
     */
    private function getCurrencyCodes(bool $type)
    {
        $method = CBR::METHOD_GET_CURRENCY_CODES;

        return $this->soapClient->$method([
            'Seld' => $type
        ]);
    }

    /**
     * @return SoapClient
     * @throws SoapFault
     */
    private function createSoapClient(): ?SoapClient
    {
        if ($this->soapClient === null) {
            $this->soapClient = new SoapClient(CBR::SOURCE, $this->options);
        }

        return $this->soapClient;
    }

}