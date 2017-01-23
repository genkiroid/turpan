# Turpan

[![Build Status](https://travis-ci.org/genkiroid/turpan.svg?branch=master)](https://travis-ci.org/genkiroid/turpan)

Turpan will check that whether deletion of PHP require statements is safe.

## Usage

```shell
vendor/bin/turpan {from rev} HEAD
```

If deleted (include|include_once|require|require_once) statements was not pure class file reference, Turpan show warnings.
Pure class file means that only contains class declaration and (include|include_once|require|require_once) statements.


If you want to not display code, please set environment variable like below.

```shell
$ TURPAN_SHOW_DETAIL=OFF vendor/bin/turpan {from rev} {to rev}
```

If you want to ignore check, please set environment variable like below.

```shell
$ IGNORE_PATTERN=/tests/ vendor/bin/turpan {from rev} {to rev}
```
