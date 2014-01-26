<?php

namespace Gobie\Regex;

class RegexException extends \RuntimeException
{

    public function __construct($message, $code = null, $pattern = null)
    {
        if (!$message) {
            $message = 'Unknown error';
        }

        if ($pattern !== null) {
            $message .= '; pattern: ' . \implode(', ', (array) $pattern);
        }

        parent::__construct($message, $code);
    }

    public function getShortMessage()
    {
        $msg = $this->getMessage();
        if (\strpos($msg, '(): ') !== false) {
            list(, $msg) = \explode('(): ', $msg);
        }

        return $msg;
    }
}
