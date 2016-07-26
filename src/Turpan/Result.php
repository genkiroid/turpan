<?php
namespace Genkiroid\Turpan;

class Result
{
    const PASS = 0;
    const FAIL = 1;
    const ERROR = 2;

    /**
     * __construct
     *
     * @param int $result
     * @param string $path
     * @param string $content
     * @return void
     */
    public function __construct($result, $message, $content = '')
    {
        $this->result = $result;
        $this->message = $message;
        $this->content = $content;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * getContent
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * getResult
     *
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }
}
