<?php
require __DIR__ . '/../vendor/autoload.php';

use Gitonomy\Git\Admin;

const TEST_REPO_URI = 'https://github.com/genkiroid/foo.git';
const TEST_REPO_DIR = '/tmp/genkiroid/turpan/foo';

exec('rm -rf ' . TEST_REPO_DIR);
Admin::cloneBranchTo(TEST_REPO_DIR, TEST_REPO_URI, 'master', false);
