# Project2. Differ.
[![Maintainability](https://api.codeclimate.com/v1/badges/f40fed1f97b14de37c08/maintainability)](https://codeclimate.com/github/zhekavafiev/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/f40fed1f97b14de37c08/test_coverage)](https://codeclimate.com/github/zhekavafiev/php-project-lvl2/test_coverage)
[![Build Status](https://travis-ci.org/zhekavafiev/php-project-lvl2.svg?branch=master)](https://travis-ci.org/zhekavafiev/php-project-lvl2)
![](https://github.com/actions/php-project-lvl2/workflowsDiffer-CI/badge.svg)
***
## Install

`composer global require evgvfv/php-project-lvl2`
***
## Appointment package

Comparing data on 2 files (json, yml) and presentation diffrents to the string with brace, json format or plain. The package works with flat and nested data.

### Example

`gendiff -h` - properties  
`gendiff -v` - version  
`gendiff --format plain before.json after.json` - get diffrents on plain format  
`gendiff --format json before.json after.json` - get diffrents on json format  
`gendiff before.json after.json` - get diffrents on standart format (string with brace)  
***
## Usage example

### With flat data

[![asciicast](https://asciinema.org/a/319701.png)](https://asciinema.org/a/319701)

### With nested data

[![asciicast](https://asciinema.org/a/319711.png)](https://asciinema.org/a/319711)

### With other format (plain)

[![asciicast](https://asciinema.org/a/319715.png)](https://asciinema.org/a/319715)

### With other format (json)

[![asciicast](https://asciinema.org/a/319716.png)](https://asciinema.org/a/319716)
    
