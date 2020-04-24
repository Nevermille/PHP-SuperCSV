# PHP SuperCSV
A little library for CSV reading and writing in PHP

[![Build Status](https://travis-ci.com/Nevermille/PHP-SuperCSV.svg?branch=master)](https://travis-ci.com/Nevermille/PHP-SuperCSV) [![BCH compliance](https://bettercodehub.com/edge/badge/Nevermille/PHP-SuperCSV?branch=master)](https://bettercodehub.com/) [![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

## Overview

A simple PHP library for reading and writing CSV files.

## Compatibility

This library has been tested for PHP 7.3 and higher

## Installation

Just use composer in your project:

```
composer require lianhua/supercsv
```

If you don't use composer, clone or download this repository, all you need is inside the src directory.

## Usage
### Read a CSV file
#### Raw data

Let's say we have the following CSV file:

```
A,B,C,D
E,F,G,H
```

Create a new Reader and then, you can read line by line with the read method:

```php
$csv = new \Lianhua\SuperCSV\Reader("/path/to/csv");
$csv->read(); // ["A", "B", "C", "D"]
$csv->read(); // ["E", "F", "G", "H"]
```

You also can read the entire file at once with the readAll method:

```php
$csv = new \Lianhua\SuperCSV\Reader("/path/to/csv");
$csv->readAll(); // [["A", "B", "C", "D"], ["E", "F", "G", "H"]]
```

By default, the reader considers the file as comma separated, you can change the format directly with the constructor along with enclosure and escape chars:

```php
$csv = new \Lianhua\SuperCSV\Reader("/path/to/csv", ";", "'", "!");
```

#### With header

If your csv has a header, make sure to call the getHeader method. The reader will create an assoc array when reading.

CSV file:

```
English,French
Apple,Pomme
Pear,Poire
```

Reading:

```php
$csv = new \Lianhua\SuperCSV\Reader("/path/to/csv");
$csv->getHeader();
$csv->read(); // ["English" => "Apple", "French" => "Pomme"]
$csv->read(); // ["English" => "Pear", "French" => "Poire"]
```

### Write a CSV file
#### Raw data

Create a Writer and use the write method:

```php
$csv = new \Lianhua\SuperCSV\Writer("/path/to/csv");
$csv->write(["A", "B", "C", "D"]);
$csv->write(["E", "F", "G", "H"]);
```

You'll get this CSV file:

```
A,B,C,D
E,F,G,H
```

You can write all data at once with the writeAll method:

```php
$csv = new \Lianhua\SuperCSV\Writer("/path/to/csv");
$csv->writeAll([["A", "B", "C", "D"], ["E", "F", "G", "H"]]);
```

By default, the writer will overwrite the file, if you want to append instead, give false in second constructor's parameter:

```php
$csv = new \Lianhua\SuperCSV\Writer("/path/to/csv", false);
```

Of course, you can change the format as well:

```php
$csv = new \Lianhua\SuperCSV\Writer("/path/to/csv", true, ";");
$csv->write(["A", "B", "C", "D"]);
$csv->write(["E", "F", "G", "H"]);
```

You'll get this CSV file:

```
A;B;C;D
E;F;G;H
```

#### With header

You can write csv with header with the setHeader method. You can give assoc arrays after that:

```php
$csv = new \Lianhua\SuperCSV\Reader("/path/to/csv");
$csv->setHeader(["English", "French"]);
$csv->write(["English" => "Apple", "French" => "Pomme"]);
$csv->write(["French" => "Poire", "English" => "Pear"]);
```

You'll get this CSV file:

```
English,French
Apple,Pomme
Pear,Poire
```
