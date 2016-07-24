<?php
namespace Genkiroid\Turpan;

class Result
{
    const PASS = 0;
    const FAIL = 1;

    /**
     * __construct
     *
     * @param int $result
     * @param string $path
     * @param string $content
     * @return void
     */
    public function __construct($result, $path, $content = '')
    {
        $this->result = $result;
        $this->path = $path;
        $this->content = $content;

        switch ($result) {
        case Result::PASS:
            $this->message = "{$path} is pure class file.";
            break;
        case Result::FAIL:
            $this->message = "{$path} is not pure class file.";
            break;
        default:
            throw new Exception('unknown result code.');
        }
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
