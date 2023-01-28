# Turpan

[![build](https://github.com/genkiroid/turpan/actions/workflows/ci.yml/badge.svg)](https://github.com/genkiroid/turpan/actions/workflows/ci.yml)

Turpan will check that whether deletion of PHP require statements is safe.

## Usage

```shell
vendor/bin/turpan {from rev} HEAD
```

If deleted (include|include_once|require|require_once) statements was not pure class file reference, Turpan show warnings.
Pure class file means that only contains class declaration and (include|include_once|require|require_once) statements.

## Environment variables

name | example | default | description
--- | --- | --- | ---
TURPAN_SHOW_DETAIL | OFF | - | Not display code in report.
IGNORE_PATTERN | /tests/ | - | Ignore matched path.
DOC_ROOT_DIR | app | - | Specify document root.

