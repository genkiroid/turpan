<?php
use Turpan\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->result = new Result(
            Result::PASS,
            'This is message',
            'This is content'
        );
    }

    public function testMessage()
    {
        $this->assertEquals('This is message', $this->result->getMessage());
    }

    public function testContent()
    {
        $this->assertEquals('This is content', $this->result->getContent());
    }

    public function testResult()
    {
        $this->assertEquals(Result::PASS, $this->result->getResult());
    }
}
