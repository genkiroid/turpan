Parse PHP a little.

## Usage

Create example.php for example.

```php
<?php
error_reporting(E_ALL && ~E_NOTICE);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Turpan\Turpan;

Turpan::run(
    dirname(__DIR__),                             //git repository path
    '1378949ebd23108b12bb0491f8e17684d142a285',   //commit from
    '1826b68b66782d4920e0bfdeefd54a84aa0eb5e0'    //commit to
);
```

Then execute it.

```shell
$ php example.php
```

It will show that is or is not modified (include|include_once|require|require_once) statement pointing pure class file in commit range.

Pure class file is only contains class declaration or (include|include_once|require|require_once) statement.

If you want to use quietly, please set environment variable.

```shell
$ TURPAN_SHOW_DETAIL=OFF php example.php
```

