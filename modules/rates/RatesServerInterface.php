<?php /** @noinspection PhpUnused */

namespace app\modules\rates;

interface RatesServerInterface
{
    public function getList(): array;

    public function getExchange($from, $to): string;

    public function getHistory($from, $to, $period): string;
}
