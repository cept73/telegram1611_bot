<?php

namespace app\models\Telegram;

use JsonException;

/**
 * @property-read Input $input
 */
class Telegram extends \aki\telegram\Telegram
{
    /**
     * @var Input
     */
    private $_input;

    /**
     * @return Input
     * @throws JsonException
     */
    protected function getInput(): ?Input
    {
        if (empty($this->_input)) {
            $input = file_get_contents('php://input');
            if (!$input) {
                $this->_input = null;
            } else {
                $array = json_decode($input, true, 512, JSON_THROW_ON_ERROR);
                $this->_input = new Input($array);
            }
        }

        return $this->_input;
    }
}
