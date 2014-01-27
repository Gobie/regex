<?php

namespace Gobie\Regex;

class RegexException extends \RuntimeException
{

    /**
     * If pattern is provided, it is appended to the message.
     *
     * @param string          $message Message
     * @param int             $code    Code
     * @param string|string[] $pattern Pattern
     */
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

    /**
     * Return exception message without function name.
     *
     * Usual exception message looks like this:
     * <pre>
     * preg_match(): Some error occurred
     * </pre>
     * So we strip the "function(): " part.
     *
     * @return string
     */
    public function getShortMessage()
    {
        $msg = $this->getMessage();
        if (\strpos($msg, '(): ') !== false) {
            list(, $msg) = \explode('(): ', $msg);
        }

        return $msg;
    }
}
