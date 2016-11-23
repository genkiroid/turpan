<?php
namespace Turpan;

class Report
{
    /**
     * passCnt
     *
     * @var int
     **/
    private $passCnt = 0;

    /**
     * failCnt
     *
     * @var int
     **/
    private $failCnt = 0;

    /**
     * errCnt
     *
     * @var int
     **/
    private $errCnt = 0;

    /**
     * totalCnt
     *
     * @var int
     **/
    private $totalCnt = 0;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(array $results)
    {
        $this->results = $results;
    }

    /**
     * addResult
     *
     * @param Result $result
     * @return Report
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * output
     *
     * @return void
     */
    public function output()
    {
        $pass = [];
        $fail = [];
        $error = [];

        echo PHP_EOL, PHP_EOL;

        foreach ($this->results as $result) {
            switch ($result->getResult()) {
            case Result::PASS:
                $this->passCnt++;
                $pass[] = $result;
                break;
            case Result::FAIL:
                $this->failCnt++;
                $fail[] = $result;
                break;
            case Result::ERROR:
                $this->errCnt++;
                $error[] = $result;
                break;
            default:
                throw new Exception('unknown result.');
            }
            $this->totalCnt++;
        }

        echo "Total: {$this->totalCnt}\n";
        echo "Pass:  {$this->passCnt}\n";
        echo "Fail:  {$this->failCnt}\n";
        echo "Error: {$this->errCnt}\n";

        echo PHP_EOL;

        echo "Failure details:\n\n";

        $i = 1;
        foreach ($fail as $result) {
            $detail = '';
            if (getenv('TURPAN_SHOW_DETAIL') !== 'OFF') {
                $detail = "See the code bellow.\n\n";
                $detail .= "\033[35m{$result->getContent()}\033[0m";
            }
            echo <<<EOT
{$i}) {$result->getMessage()} {$detail}


EOT;
            $i++;
        }

        echo "Error details:\n\n";

        $i = 1;
        foreach ($error as $result) {
            echo <<<EOT
{$i}) {$result->getMessage()}


EOT;
            $i++;
        }
    }
}
