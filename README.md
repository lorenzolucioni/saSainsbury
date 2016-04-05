# sainsbury-test
# repos: https://github.com/lorenzolucioni/sainsbury-test

Sainsbury’s Software Engineering Test - Console application that scrapes the Sainsbury’s grocery site - Ripe Fruits page and returns a JSON array of all the products on the page.
by Lorenzo Lucioni

------
RUN
---
How to run the App (no external libraries required or dependencies):
user@host:/src/sainsbury-test$ php console-app.php 

------
TEST
---
How to run PHPUnit tests (PHPUnit version used: 4.8.24):
user@host:/src/sainsbury-test$ phpunit unit-test.php

------
FILES
---
Project PHP classes:
PageParserClass.php
ProductPageClass.php
ConsoleExceptionClass.php

Project PHP running script:
console-app.php

PHPUnit Tests file:
unit-test.php

PHPUnit data 'mock', used by PHPUnit Tests:
listMock.html
productMock.html
(they are essentially a copy of the business pages, ideally they would be a simplified html version of them)

------
