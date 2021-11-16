<?php /** @noinspection SpellCheckingInspection, PhpMissingFieldTypeInspection, PhpUnused */

namespace app\controllers;

use app\models\Chat;
use app\modules\chat\ChatService;
use app\modules\listing\ListingRepository;
use app\modules\listing\ListingSerializer;
use JsonException;
use Yii;
use yii\base\InvalidConfigException;

class RequestController extends base\RequestController
{
    /** @var string[] */
    public const COMMANDS_LIST = ['help', 'list', 'lst', 'exchange', 'history'];

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
    public function commandHelp(): void
    {
        $this->chatService->sendMessageOrNothing(Chat::TEXT_HELP);
    }

    /**
     * @throws JsonException
     */
    public function commandList(): void
    {
        $ratesList = '1234';

        $this->chatService->sendMessageOrNothing($ratesList);
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
        $this->chatService->sendMessageOrNothing(Chat::TEXT_HELP);
    }

    /**
     * @param $params
     * @param $isAdmin
     * @throws JsonException
     * @noinspection PhpUnusedParameterInspection
     */
    public function commandHistory($params, $isAdmin): void
    {
        $this->chatService->sendMessageOrNothing(Chat::TEXT_HELP);
    }
}
