<?php

namespace App\Client;


abstract class CBR
{
    public const SOURCE = 'https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';

    public const NAME_RUB = 'Российский рубль';

    public const NUMERIC_CODE_RUB = '643';

    public const SYMBOL_CODE_RUB = 'RUB';

    /**
     * EnumValutes(Seld) 
     * полный перечень валют
     */
    public const METHOD_GET_CURRENCY_CODES = 'EnumValutes';

    /**
     * GetCursDynamic() 
     * получение курса валюты за определенный период
     */
    public const METHOD_GET_DYNAMIC_RATE = 'GetCursDynamic';
}