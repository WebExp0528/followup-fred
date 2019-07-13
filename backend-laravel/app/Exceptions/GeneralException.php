<?php
namespace App\Exceptions;

use Exception;

class GeneralException extends Exception{
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

    public function __construct($error = null, $errorMessage = 'default', $status = 422)
    {
        parent::__construct('The given data was invalid.');

        $this->error = $error;
        $this->errorMessage = $errorMessage;
        $this->status = $status;
    }
}