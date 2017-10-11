# Fetch stats for your NPM packages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/developmint/npm-stats-api.svg?style=flat-square)](https://packagist.org/packages/developmint/npm-stats-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Developmint/npm-stats-api/master.svg?style=flat-square)](https://travis-ci.org/Developmint/npm-stats-api)
[![Quality Score](https://img.shields.io/scrutinizer/g/developmint/npm-stats-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/developmint/npm-stats-api)
[![Total Downloads](https://img.shields.io/packagist/dt/developmint/npm-stats-api.svg?style=flat-square)](https://packagist.org/packages/developmint/npm-stats-api)

This package makes it easy to retrieve stats for packages hosted on [npmjs.com](https://www.npmjs.com/)

PS: It's heavily inspired by the [packagist-api package](https://github.com/spatie/packagist-api) made from Spatie
## Installation

You can install the package via composer:

``` bash
composer require developmint/npm-stats-api
```

## Usage

You must pass a Guzzle client to the constructor of `Developmint\NpmStats\NpmStats`.

``` php
$client = new \GuzzleHttp\Client();

$npmStats = new \Developmint\NpmStats\NpmStats($client);
```

### Get stats for a package of your choice
``` php
$npmStats->getStats('jquery');
```

### Get stats with a period of your choice

Use a constant provided by the NpmStats class to get stats for the last day, week, month, year or in total.
``` php
$npmStats->getStats('jquery', NpmStats::LAST_DAY);
```
You can also pass in specific dates
``` php
$npmStats->getStats('jquery', '2017-01-01');
```
Or even ranges
``` php
$npmStats->getStats('jquery', '2014-02-07:2014-02-14');
```

The output will be already decoded from json:

``` php
array(4) {
  ["downloads"]=>
  int(198672)
  ["start"]=>
  string(10) "2017-10-10"
  ["end"]=>
  string(10) "2017-10-10"
  ["package"]=>
  string(6) "jquery"
}
```

### Get stats with a period of your choice as range

You can also get the stats as a *range*, means the downloads split up per day:
``` php
$npmStats->getStats('jquery', NpmStats::LAST_WEEK, true);
```

And this would be the result of that call:

``` php
array(4) {
  ["start"]=>
  string(10) "2017-10-04"
  ["end"]=>
  string(10) "2017-10-10"
  ["package"]=>
  string(6) "jquery"
  ["downloads"]=>
  array(7) {
    [0]=>
    array(2) {
      ["downloads"]=>
      int(200678)
      ["day"]=>
      string(10) "2017-10-04"
    }
    [1]=>
    array(2) {
      ["downloads"]=>
      int(195593)
      ["day"]=>
      string(10) "2017-10-05"
    }
    [2]=>
    array(2) {
      ["downloads"]=>
      int(172132)
      ["day"]=>
      string(10) "2017-10-06"
    }
    [3]=>
    array(2) {
      ["downloads"]=>
      int(51068)
      ["day"]=>
      string(10) "2017-10-07"
    }
    [4]=>
    array(2) {
      ["downloads"]=>
      int(46892)
      ["day"]=>
      string(10) "2017-10-08"
    }
    [5]=>
    array(2) {
      ["downloads"]=>
      int(171920)
      ["day"]=>
      string(10) "2017-10-09"
    }
    [6]=>
    array(2) {
      ["downloads"]=>
      int(198672)
      ["day"]=>
      string(10) "2017-10-10"
    }
  }
}
```

### Get bulk stats

Of course you can retrieve up to 128 packages as a bulk query. Simply separate them with a command and you are good 
to go.

``` php
$npmStats->getStats('vue,express', NpmStats::LAST_WEEK);
```

But beware! This only works in point mode, not in range mode.

``` php
$npmStats->getStats('vue,express', NpmStats::LAST_WEEK, true);
//Won't work
```
Anyway, the output will look similar to the normal point mode output:

``` php 
array(2) {
  ["vue"]=>
  array(4) {
    ["downloads"]=>
    int(3980980980098089080980983)
    ["package"]=>
    string(5) "vue"
    ["start"]=>
    string(10) "2017-10-10"
    ["end"]=>
    string(10) "2017-10-10"
  }
  ["express"]=>
  array(4) {
    ["downloads"]=>
    int(818264)
    ["package"]=>
    string(7) "express"
    ["start"]=>
    string(10) "2017-10-10"
    ["end"]=>
    string(10) "2017-10-10"
  }
}
```

## More in-depth info about the NPM stats API

You can find a detailed explanation about how the stats API of NPM works in this
[repository README file](https://github.com/npm/registry/blob/master/docs/download-counts.md)

## Limits of the API

The official limits are:
+ Bulk queries: 128 packages at a time and at most 365 days of data
+ All other queries: limited to at most 18 months of data
+ Earliest date available: January 10, 2015

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email support@developmint.de instead of using the issue tracker.

## Credits

- [Alexander Lichter](https://github.com/manniL)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
