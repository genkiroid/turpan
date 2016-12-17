<?php
use Turpan\Report;
use Turpan\Result;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->report = new Report(
            [
                new Result(
                    Result::PASS,
                    'message',
                    'content'
                ),
                new Result(
                    Result::FAIL,
                    'message',
                    'content'
                ),
                new Result(
                    Result::ERROR,
                    'message',
                    'content'
                ),
            ]
        );
    }

    public function testHasResult()
    {
        $this->assertCount(3, $this->report->results);
        $this->assertInstanceOf('Turpan\Result', $this->report->results[0]);
    }

    public function testAddResult()
    {
        $this->report->addResult(
            new Result(
                Result::PASS,
                'message',
                'content'
            )
        );

        $this->assertCount(4, $this->report->results);
        $this->assertInstanceOf('Turpan\Result', $this->report->results[0]);
    }
}
