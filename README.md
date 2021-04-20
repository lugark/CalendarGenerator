# CalendarGenerator

[![Travis CI](https://img.shields.io/travis/com/lugark/CalendarGenerator?label=TravisCI)](https://travis-ci.com/github/lugark/CalendarGenerator)
[![CodeCov](https://img.shields.io/codecov/c/gh/lugark/CalendarGenerator?label=CodeCov)](https://codecov.io/gh/lugark/CalendarGenerator)
[![SonarCloud](https://img.shields.io/sonar/quality_gate/lugark_CalendarGenerator?server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/dashboard?id=lugark_CalendarGenerator)
[![Dependencies](https://img.shields.io/librariesio/github/lugark/CalendarGenerator)](https://img.shields.io/librariesio/github/lugark/CalendarGenerator)

The goal is to have a printable PDF with customized calendar including german school holidays and/or public holidays

## Dependencies
- PHP 7.4 or higher

## Installaion
```
composer install
```

## Usage 
### Rendering the Calendar
```
bin/console calendar:generate
```

### Calendar events
To also render holidays you can fetch the dates for public holidays from "Deutsche Feiertage API"  https://deutsche-feiertage-api.de 

```
bin/console calendar:fetch:holidays
```