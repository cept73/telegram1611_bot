<?php /** @noinspection PhpUnused */

namespace app\models\Telegram;

/**
 * @property mixed $edited_message
 */
class Input extends \aki\telegram\base\Input
{
    private $_edited_message;

    public function getEdited_message()
    {
	    return $this->_edited_message;
    }

    public function setEdited_message($value): void
    {
	    $this->_edited_message = $value;
    }
}
