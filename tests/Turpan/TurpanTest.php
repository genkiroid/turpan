<?php
use Turpan\Turpan;
use Turpan\Result;
use Gitonomy\Git\Repository;
use Gitonomy\Git\Admin;

class TurpanTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->repo = Turpan::getRepo(TEST_REPO_DIR);
    }

    public function testGetRepo()
    {
        $this->assertInstanceOf('Gitonomy\Git\Repository', $this->repo);
    }

    public function testGetChangedFiles()
    {
        $changes = Turpan::getChangedFiles(
            $this->repo,
            '71719ef7817047e16459d74784b2a6eb42292d17',
            'ac051f08cad57ce38e8609a941474ccb76621dd4'
        );

        $this->assertInternalType('array', $changes);
        $this->assertInstanceOf('Gitonomy\Git\Diff\File', $changes[0]);

        return $changes;
    }

    /**
     * @depends testGetChangedFiles
     */
    public function testGetRequiredFileMap(array $changes)
    {
        $map = Turpan::getRequiredFileMap($changes);

        $this->assertInternalType('array', $map);
        $this->assertEquals(TEST_REPO_DIR . '/index.php', $map[0]['file']);
        $this->assertEquals(TEST_REPO_DIR . '/hello.php', $map[0]['required_file']);

        return $map;
    }

    /**
     * @depends testGetRequiredFileMap
     */
    public function testTest(array $map)
    {
        $this->expectOutputString('genkiroid/Turpan version ' . Turpan::VERSION . "\n\n\033[31mF\033[0m");

        $results = Turpan::test($map);

        $this->assertInternalType('array', $results);
    }

    public function testExitSuccess()
    {
        $turpan = new Turpan();
        $results[] = new Result(
            Result::PASS,
            ''
        );
        $exitCode = $turpan->getExitCode($results);

        $this->assertEquals(0, $exitCode);
    }

    public function testExitError()
    {
        $turpan = new Turpan();
        $results[] = new Result(
            Result::FAIL,
            ''
        );
        $exitCode = $turpan->getExitCode($results);

        $this->assertEquals(1, $exitCode);
    }

    public function testShouldIgnoreBlobIndex()
    {
        Closure::bind(function () {
            $this->assertTrue(Turpan::shouldIgnoreBlobIndex('f9367ba'));
        }, $this, 'Turpan\Turpan')->__invoke();
    }
}
