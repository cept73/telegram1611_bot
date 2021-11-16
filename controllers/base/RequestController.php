<?php

/** @noinspection SpellCheckingInspection */
/** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpUnused */

namespace app\controllers\base;

use app\models\Telegram;
use app\modules\chat\ChatService;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;

class RequestController extends Controller
{
    /** @var bool */
    public $enableCsrfValidation = false;

    /** @var string[] */
    public const COMMANDS_LIST = [];

    /** @var Telegram\Telegram */
    public $telegram;

    /** @var ChatService */
    public $chatService;

    /**
     * RequestController constructor
     * @param $id
     * @param $module
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->telegram     = Yii::$app->get('telegram');
        $this->chatService  = Yii::createObject(ChatService::class);
    }

    /**
     * Handle Telegram webhook request
     */
    public function actionHook(): string
    {
        $messageTxt = $this->telegram->input->message->text ?? null;
        $chatId     = $this->chatService->chatId;
        $adminsList = Yii::$app->params['admins'] ?? [];
        $isAdmin    = in_array($chatId, $adminsList, true);

        foreach (self::COMMANDS_LIST as $command) {
            if (strpos($messageTxt, "/$command") === 0) {
                $methodName = 'command' . ucfirst($command);
                $params = $this->getParamsFromMessageText($command, $messageTxt);
                $this->$methodName($params, $isAdmin);
                return '';
            }
        }
        try {
            $this->chatService->onReceivedMessage($isAdmin);
        } catch (Exception $e) {
            $this->onException($e);
        }

        return '';
    }

    private function getParamsFromMessageText($command, $text)
    {
        $params = explode(' ', $text);
        if (count($params) !== 1) {
            return $params;
        }

        $result = ["/$command"];
        $textAfterCommand = substr($text, strlen($command) + 1);
        if ($textAfterCommand) {
            $result[] = $textAfterCommand;
        }

        return $result;
    }

    /**
     * Show error response
     *
     * @return string
     */
    public function actionError(): string
    {
        return $this->render('not-found');
    }

    /**
     * Set webhook to page
     *
     * @return string
     */
    public function actionSet(): string
    {
        try {
            $result = $this->telegram->setWebhook([
                'url' => Yii::$app->params['webhook']['url'],
            ]);
        } catch (Exception $e) {
            return $this->onException($e);
        }

        return $result;
    }

    /**
     * Unset webhook
     *
     * @return string
     */
    public function actionUnset(): string
    {
        try {
            $this->telegram->deleteWebhook();
        } catch (Exception $e) {
            return $this->onException($e);
        }

        return '';
    }

    /**
     * @param $e
     * @return string
     */
    private function onException($e): string
    {
        Yii::debug($e->getMessage());

        return $this->render('exception', ['exception' => $e]);
    }
}
