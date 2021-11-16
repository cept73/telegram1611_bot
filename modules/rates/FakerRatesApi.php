<?php /** @noinspection PhpUnused */

namespace app\rates;

use app\modules\rates\RatesServerInterface;

class FakerRatesApi implements RatesServerInterface
{
    public function getList(): array
    {
        return [
            'DKK' => 6.74,
            'HUF' => 299.56
        ];
    }

    public function getExchange($from, $to): string
    {
        return '$15.55';
    }

    public function getHistory($from, $to, $period): string
    {
        return '';
    }
}
