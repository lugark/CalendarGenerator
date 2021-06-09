# CalendarGenerator
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Travis CI](https://img.shields.io/travis/com/lugark/CalendarGenerator?label=TravisCI)](https://travis-ci.com/github/lugark/CalendarGenerator)
[![CodeCov](https://img.shields.io/codecov/c/gh/lugark/CalendarGenerator?label=CodeCov)](https://codecov.io/gh/lugark/CalendarGenerator)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=lugark_CalendarGenerator&metric=alert_status)](https://sonarcloud.io/dashboard?id=lugark_CalendarGenerator)
[![Dependencies](https://img.shields.io/librariesio/github/lugark/CalendarGenerator)](https://img.shields.io/librariesio/github/lugark/CalendarGenerator)

The goal is to have a printable PDF with customized calendar including german school holidays and/or public holidays

## Dependencies
- PHP 7.4 or higher
- Symfony 5.3
- "Feiertage" can be fetched from https://deutsche-feiertage-api.de/
- "Schulferien" can be fetched from [mehr-schulferien.de](https://www.mehr-schulferien.de/)
- [aeon-php/calendar](https://github.com/aeon-php/calendar) to iterate/calculate calendar
- [mpdf/mpdf](https://github.com/mpdf/mpdf) to generate the PDF 
## Installaion
```
composer install
```

## Usage 
### Rendering the Calendar
To render a calender you need to provide at least a startdate:
```
bin/console calendar:generate 2020-01
```
<img width="400" height="auto" src="docs/images/Calendar.png" alt="Generated calendar" />

If you want to render holidays like german bank holidays or school vacation you can provide it with either *--publicholidays* or *--schoolholidays*.
Both require the federal country to be specified (as those dates differ in germany)
```
bin/console calendar:generate --publicholidays BY 2020-01
```
<img width="400" height="auto" src="docs/images/CalendarDifferentStart_Holidays.png" alt="Calendar with different start and holidays" />

### Calendar events
To also render holidays you can fetch the dates for public holidays from "Deutsche Feiertage API"  https://deutsche-feiertage-api.de 

```
bin/console calendar:fetch:holidays --year 2021 public
```

To fetch the dates for german school vacations from [mehr-schulferien.de](https://www.mehr-schulferien.de/)
```
bin/console calendar:fetch:holidays --year 2021 school
```