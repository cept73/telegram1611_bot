<?php

namespace app\modules\listing;

use app\models\Telegram;
use Yii;
use yii\base\InvalidConfigException;

class ListingService
{
    /** @var Telegram\Telegram */
    private $telegram;

    /**
     * listingService constructor.
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        $this->telegram = Yii::$app->get('telegram');
    }

    public function parseFromUrl($url): string
    {
        $scanCommand = 'python3 scripts/read-list.py "' . $url . '" >/tmp/last.log';
        exec($scanCommand, $output);

        return implode("\n", $output);
    }

    public function getUploadedFileUrl(): ?string
    {
        $telegram = $this->telegram;

        $document = $telegram->input->message->document ?? [];
        if (!$document) {
            return null;
        }

        /** @var array $response */
        $response = $telegram->getFile(['file_id' => $document['file_id']]);

        if (!($responseResult = $response['result'] ?? null)) {
            return null;
        }

        if (!($filePath = $responseResult['file_path'])) {
            return null;
        }

        return 'https://api.telegram.org/file/bot' . $telegram->botToken . '/' . $filePath;
    }
}
