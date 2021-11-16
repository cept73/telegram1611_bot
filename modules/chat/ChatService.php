<?php

namespace app\modules\chat;

use app\models\Chat;
use app\models\Telegram\Telegram;
use app\models\User;
use app\modules\listing\ListingRepository;
use app\modules\listing\ListingSerializer;
use app\modules\listing\ListingService;
use app\modules\user\UserService;
use Exception;
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

    /** @var ListingService */
    private $listingService;

    /**
     * ChatService constructor.
     * @param ListingService $listingService
     * @throws InvalidConfigException
     */
    public function __construct(ListingService $listingService)
    {
        parent::__construct();

        $this->listingService = $listingService;

        $this->telegram = Yii::$app->get('telegram');
        $currentChat    = $this->getCurrentChat();
        $this->chatId   = $currentChat->id ?? null;
        $this->user     = UserService::getOrCreateUserByChat($currentChat);
    }

    public function getCurrentChat()
    {
        return $this->telegram->input->message->chat ?? null;
    }

    /**
     * @param $ticker
     * @param bool $isAdmin
     * @return bool
     * @throws InvalidConfigException
     * @throws JsonException
     * @noinspection PhpUnusedParameterInspection
     */
    public function executeStartCommandWithTicker($ticker, $isAdmin = false): bool
    {
        if ($ticker) {
            $this->sendTickerInfo($ticker);
            return true;
        }

        return false;
    }

    /**
     * @param false $isAdmin
     * @throws InvalidConfigException
     * @throws JsonException
     */
    public function onReceivedMessage($isAdmin = false): void
    {
        $messageText        = $this->telegram->input->message->text ?? null;
        $uploadedFileUrl    = $this->listingService->getUploadedFileUrl();

        if ($uploadedFileUrl) {
            if (!$isAdmin) {
                Yii::error('Unauthorized file');
                return;
            }

	    set_time_limit(10 * 60);
            try {
                $this->sendMessageOrNothing(Chat::TEXT_LIST_SAVING);
                $this->listingService->parseFromUrl($uploadedFileUrl);
                $this->sendMessageOrNothing(Chat::TEXT_LIST_SAVED);
            } catch (Exception $ex) {
                Yii::error($ex->getMessage());
                $this->sendMessageOrNothing(Chat::TEXT_LIST_SAVE_FAIL);
            }
        }

        if ($messageText) {
            $this->sendTickerInfo($messageText);
        }
    }

    /**
     * @param $ticker
     * @throws InvalidConfigException
     * @throws JsonException
     */
    public function sendTickerInfo($ticker): void
    {
        $listingRepository = Yii::createObject(ListingRepository::class);
	    $listingResults = $listingRepository->searchByTicker($ticker);
        $info = ListingSerializer::getInfo($listingResults);
        $this->sendMessageOrNothing($info);
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
