<?php

namespace App\Exceptions;

class AssemblerException extends \Exception implements AssemblerExceptionInterface
{
    protected $code = 400;

    public function __construct(int $code = 0)
    {
        parent::__construct($this->getErrorMessage($code), $this->code);
    }

    private function getErrorMessage($code): string
    {
        return match ($code) {
            self::HAS_ACTIVE_ORDER      => 'У вас есть незаконченные заказы. Дособерите их!',
            self::OFFLINE               => 'Выйдите на смену, чтобы собирать заказы.',
            self::ORDER_NOT_COMPLETED   => 'Заказ был собран не до конца. Положите все продукты, чтобы завершить сборку.',
            self::NO_ONLINE_USERS       => 'Нет рабочих на смене в данном магазине.',
            default                     => 'Невозможно совершить действие.'
        };
    }
}
