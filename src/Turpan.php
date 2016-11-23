<?php
namespace Turpan;

use Gitonomy\Git\Repository;
use Gitonomy\Git\Diff\FileChange;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

class Turpan
{
    const VERSION = '0.2.6';

    const INCLUDE_STMT_PATTERN = '/^( *)(include_once|include|require_once|require)(\((?P<required_file_1>.*)\)| +(?P<required_file_2>.*));( *)$/';

    /**
     * getRepo
     *
     * @param string $path git repository path
     * @return Gitonomy\Git\Repository
     */
    public static function getRepo($path)
    {
        return new Repository($path);
    }

    /**
     * getChangedFiles
     *
     * @param Gitonomy\Git\Repository $repo
     * @param string $revFrom
     * @param string $revTo
     * @return array
     */
    public static function getChangedFiles(Repository $repo, $revFrom, $revTo)
    {
        return $repo->getDiff("{$revFrom}..{$revTo}")->getFiles();
    }

    /**
     * getRequiredFileMap
     *
     * @param array $files Array of Gitonomy\Git\Diff\File
     * @return array
     */
    public static function getRequiredFileMap(array $files)
    {
        $map = [];

        foreach ($files as $file) {
            $changes = $file->getChanges();
            foreach ($changes as $change) {
                $lines = $change->getLines();
                foreach ($lines as $data) {
                    list($type, $line) = $data;
                    if ($type === FileChange::LINE_ADD || $type === FileChange::LINE_REMOVE) {
                        $isMatch = preg_match(Turpan::INCLUDE_STMT_PATTERN, $line, $matches);
                        if (!$isMatch) { continue; }

                        $tmp['file'] = realpath($file->getOldName());
                        $tmp['required_file'] = (!empty($matches['required_file_1'])) ? $matches['required_file_1'] : $matches['required_file_2'];
                        $tmp['required_file'] = str_replace('__FILE__', "'{$tmp['file']}'", $tmp['required_file']);
                        array_push($map, $tmp);
                    }
                }
            }
        }
        return $map;
    }

    /**
     * isAllowedNode
     *
     * @param PhpParser\Node $node
     * @return bool
     */
    protected function isAllowedNode(\PhpParser\Node $node)
    {
        switch ($node->getType()) {
        case 'Stmt_Class':
        case 'Stmt_Interface':
        case 'Expr_Include':
            return true;
            break;
        default:
            return false;
            break;
        }
    }

    /**
     * isPureClassFile
     *
     * @param array $nodes Array of PhpParser\Node
     * @return bool
     */
    public static function isPureClassFile(array $nodes)
    {
        foreach ($nodes as $node) {
            if (self::isAllowedNode($node)) { continue; }
            return false;
        }
        return true;
    }

    /**
     * getDeniedNode
     *
     * @param array $nodes Array of PhpParser\Node
     * @return string
     */
    protected function getDeniedNode(array $nodes)
    {
        foreach ($nodes as $node) {
            if (self::isAllowedNode($node)) { continue; }
            return (new PrettyPrinter\Standard)->prettyPrintFile(array($node));
        }
        return '';
    }

    /**
     * test
     *
     * @param array $map
     * @return array of Result
     */
    public static function test(array $map)
    {
        printf("genkiroid/Turpan version %s\n\n", self::VERSION);

        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP5);
        $results = [];

        foreach ($map as $m) {
            if (file_exists(dirname($m['file']))) {
                chdir(dirname($m['file']));
            }

            $requiredPath = eval('return ' . $m['required_file'] . ';');
            if (is_readable($requiredPath) === false) {
                echo "\033[34mE\033[0m";
                $results[] = new Result(
                    Result::ERROR,
                    "{$m['file']} requires \33[33m{$m['required_file']}\033[0m, but it is not readable."
                );
                continue;
            }

            $requiredContent = file_get_contents($requiredPath);
            $nodes = $parser->parse($requiredContent);

            if (self::isPureClassFile($nodes)) {
                echo "\033[32m.\033[0m";
                $results[] = new Result(
                    Result::PASS,
                    "{$requiredPath} is pure class file."
                );
            } else {
                echo "\033[31mF\033[0m";
                $results[] = new Result(
                    Result::FAIL,
                    "{$m['file']} requires \33[33m{$m['required_file']}\033[0m, but it is not pure class file.",
                    self::getDeniedNode($nodes)
                );
            }
        }

        return $results;
    }

    /**
     * report
     *
     * @param array $results Array of Result
     * @return void
     */
    public static function report(array $results)
    {
        (new Report($results))->output();
    }

    /**
     * run
     *
     * @param string $repoPath
     * @param string $revFrom
     * @param string $revTo
     * @return void
     */
    public static function run($repoPath, $revFrom, $revTo)
    {
        Turpan::report(
            Turpan::test(
                Turpan::getRequiredFileMap(
                    Turpan::getChangedFiles(
                        Turpan::getRepo($repoPath),
                        $revFrom,
                        $revTo
                    )
                )
            )
        );
    }
}
