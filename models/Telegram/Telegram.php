<?php /** @noinspection PhpUnused */

namespace app\models\Telegram;

use aki\telegram\base\Input;
use JsonException;

/**
 * @property-read null|string $uploadedFileUrl
 * @property-read null $currentChat
 * @property-read null $currentMessage
 * @property-read Input $input
 */
class Telegram extends \aki\telegram\Telegram
{
    /**
     * @var Input
     */
    private $_input;

    /**
     * @return ?Input
     * @throws JsonException
     */
    protected function getInput(): ?Input
    {
        if (empty($this->_input)) {
            $input = file_get_contents('php://input');
            if (!$input) {
                $this->_input = null;
                return null;
            }

            $array = json_decode($input, true, 512, JSON_THROW_ON_ERROR);
            $this->_input = new Input($array);
        }

        return $this->_input;
    }

    public function getCurrentChat()
    {
        return $this->input->message->chat ?? null;
    }

    public function getCurrentMessage()
    {
        return $this->input->message->text ?? null;
    }

    public function getUploadedFileUrl(): ?string
    {
        $document = $this->telegram->input->message->document ?? [];
        if (!$document) {
            return null;
        }

        /** @var array $response */
        $response = $this->getFile(['file_id' => $document['file_id']]);

        $responseResult = $response['result'] ?? null;
        if (!$responseResult) {
            return null;
        }

        $filePath = $responseResult['file_path'];
        if (!$filePath) {
            return null;
        }

        return 'https://api.telegram.org/file/bot' . $this->botToken . '/' . $filePath;
    }
}
