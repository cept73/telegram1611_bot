<?php

namespace app\models;

class Chat
{
    public const TEXT_HELP          = <<<TEXT
\xF0\x9F\x92\xB9	*HELP WITH CURRENCY BOT*
    
/list`        `- Get all available rates

/lst`        ` - Get all available rates

`/exchange ` - Converts with two decimal precision.
`          `   Format: `$10 to CAD`; `10 USD to CAD`

`/history  ` - Get history for period.
`          `   Format: `USD/CAD for 7 days`

\xF0\x9F\x92\xA1	Show help: /help
TEXT;

    public const PARSE_MODE         = 'Markdown';
    public const TEXT_MAX_LENGTH    = 750;
}
