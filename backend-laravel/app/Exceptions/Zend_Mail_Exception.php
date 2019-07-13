<?php
namespace App\Exceptions;

use Exception;

class Zend_Mail_Exception extends Exception{
    /**
     * The recommended response to send to the client.
     *
     * @var \Symfony\Component\HttpFoundation\Response|null
     */
    public $error;

    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $status = 422;

    /**
     * The name of the error bag.
     *
     * @var string
     */
    public $errorMessage;

    public function __construct($errorMessage = 'default', $error = 'Zend Mail Error', $status = 400)
    {
        $this->error = $error;
        $this->errorMessage = $errorMessage;
        $this->status = $status;
    }
}