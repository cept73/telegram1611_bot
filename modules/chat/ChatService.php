<?php

namespace app\modules\chat;

use app\models\Chat;
use app\models\Telegram\Telegram;
use app\models\User;
use app\modules\user\UserService;
use JsonException;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 *
 * @property-read string $currentButtons
 * @property-read null $currentChat
 */
class ChatService extends BaseObject
{
    /** @var int */
    public $chatId;

    /** @var ?User */
    public $user;

    /** @var Telegram */
    private $telegram;

    /**
     * ChatService constructor
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        parent::__construct();

        $this->telegram = Yii::$app->get('telegram');
        $currentChat    = $this->telegram->getCurrentChat();
        $this->chatId   = $currentChat->id ?? null;
        $this->user     = UserService::getOrCreateUserByChat($currentChat);
    }

    /**
     * @param false $isAdmin
     * @throws JsonException
     */
    public function onReceivedMessage(bool $isAdmin = false): void
    {
        $messageText        = $this->telegram->getCurrentMessage();
        $uploadedFileUrl    = $this->telegram->getUploadedFileUrl();

        if ($uploadedFileUrl && !$isAdmin) {
            Yii::error('Unauthorized file');
            return;
        }

        if ($messageText) {
            $this->sendInfo($messageText);
        }
    }

    /**
     * @throws JsonException
     */
    public function sendInfo($message): void
    {
        $this->sendMessageOrNothing($message);
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function getCurrentButtons(): string
    {
        $buttons = [
            /* [
                ['text' => Chat::TEXT_BTN_LIST],
                ['text' => Chat::TEXT_BTN_HELP],
            ]*/
        ];

        return json_encode(['keyboard' => $buttons], JSON_THROW_ON_ERROR);
    }

    /**
     * @param $message
     * @throws JsonException
     */
    public function sendMessageOrNothing($message): void
    {
        while (!empty($message)) {
            $messageToSend = substr($message, 0, Chat::TEXT_MAX_LENGTH);

            if (strlen($messageToSend) === Chat::TEXT_MAX_LENGTH) {
                $lastSpacePos = strrpos($messageToSend, "\n");
                if (!$lastSpacePos) {
                    $lastSpacePos = strrpos($messageToSend, ' ');
                }
                if (!$lastSpacePos) {
                    $lastSpacePos = Chat::TEXT_MAX_LENGTH;
                }
                $messageToSend = substr($messageToSend, 0, $lastSpacePos);
            } else {
                $lastSpacePos = strlen($messageToSend);
            }

            $this->telegram->sendMessage([
                'chat_id'       => $this->chatId,
                'text'          => $messageToSend,
                'parse_mode'    => Chat::PARSE_MODE,
                'reply_markup'  => $this->getCurrentButtons()
            ]);

            $message = trim(substr($message, $lastSpacePos));
        }
    }
}
