<?php
    /** @var Exception $exception */

    /** @noinspection PhpUnhandledExceptionInspection */
    echo json_encode(['error' => 'exception', 'message' => $exception->getMessage()], JSON_THROW_ON_ERROR);
