<?php

namespace app\models;

class Chat
{
    public const TEXT_HELP          = 'Это помощь от робота';
    public const TEXT_LIST_SAVING   = 'Сохраняем содержимое файла';
    public const TEXT_LIST_SAVE_FAIL = 'Не удалось обработать файл';
    public const TEXT_LIST_SAVED    = 'Информация обновлена';
    public const TEXT_LAST_UPDATE   = '_Последнее обновление: %s_';
    public const TICKER_SHOW_ALL    = 'ALL';
    public const PARSE_MODE         = 'Markdown';
    public const TEXT_MAX_LENGTH    = 750;
}
