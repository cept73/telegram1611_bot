<?php /** @noinspection SpellCheckingInspection, PhpMissingFieldTypeInspection, PhpUnused */

namespace app\controllers;

use app\models\Chat;
use JsonException;

class RequestController extends base\RequestController
{
    /** @var string[] */
    public $commandsList = ['start', 'help', 'list', 'lst', 'exchange', 'history'];

    /**
     * Start page return empty
     */
    public function actionIndex(): string
    {
        return '';
    }

    /**
     * @throws JsonException
     */
    public function commandStart(): void
    {
        $this->commandHelp();
    }

    /**
     * @throws JsonException
     */
    public function commandHelp(): void
    {
        $this->chatService->sendMessageOrNothing(Chat::TEXT_HELP);
    }

    /**
     * @throws JsonException
     */
    public function commandList(): void
    {
        $ratesList = $this->ratesService->getList();
        $ratesString = json_encode($ratesList, JSON_THROW_ON_ERROR);

        $this->chatService->sendMessageOrNothing($ratesString);
    }

    /**
     * @throws JsonException
     */
    public function commandLst(): void
    {
        $this->commandList();
    }

    /**
     * @param $params
     * @param $isAdmin
     * @throws JsonException
     * @noinspection PhpUnusedParameterInspection
     */
    public function commandExchange($params, $isAdmin): void
    {
        $from = 'CAD';
        $to = 'USD';
        $ratesList = $this->ratesService->getExchange($from, $to);

        $this->chatService->sendMessageOrNothing($ratesList);
    }

    /**
     * @param $params
     * @param $isAdmin
     * @throws JsonException
     * @noinspection PhpUnusedParameterInspection
     */
    public function commandHistory($params, $isAdmin): void
    {
        $from = 'CAD';
        $to = 'USD';
        $period = '7 days';
        $history = $this->ratesService->getHistory($from, $to, $period);

        $this->chatService->sendMessageOrNothing($history);
    }
}
