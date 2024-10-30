<?php

namespace Frame;

class Exception extends \Exception
{
    protected $response;

    public function __construct($message = "", $code = 0, \Exception $previous = null, $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public static function fromResponse($response)
    {
        $message = isset($response['error']['message']) ? $response['error']['message'] : 'Unknown error'; // Adjust based on API error structure
        $code = isset($response['error']['code']) ? $response['error']['code'] : 0;

        return new self($message, $code, null, $response);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public static function getErrorMessage(Exception $e)
    {
        $decodedMessage = json_decode($e->getMessage());

        return $decodedMessage->errors['0']->detail;
    }
}
