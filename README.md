# PHP проект - "Вычислитель отличий"
### Hexlet tests and linter status:
[![Actions Status](https://github.com/mkolotovich/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/mkolotovich/php-project-48/actions)
[![PHP CI](https://github.com/mkolotovich/php-project-48/actions/workflows/workflow.yml/badge.svg)](https://github.com/mkolotovich/php-project-48/actions/workflows/workflow.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/dde09a7d52b5a358a21b/maintainability)](https://codeclimate.com/github/mkolotovich/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/dde09a7d52b5a358a21b/test_coverage)](https://codeclimate.com/github/mkolotovich/php-project-48/test_coverage)

## Описание
Вычислитель отличий – программа, определяющая разницу между двумя структурами данных. Это популярная задача, для решения которой существует множество онлайн сервисов, например http://www.jsondiff.com/. Подобный механизм используется при выводе тестов или при автоматическом отслеживании изменении в конфигурационных файлах.

Возможности утилиты:

* Поддержка разных входных форматов: yaml, json
* Генерация отчета в виде plain text, stylish и json

## Установка и запуск приложения 
1. Убедитесь, что у вас установлен PHP версии 8.1 или выше. В противном случае установите PHP версии 8.1 или выше.
2. Установите пакет в систему с помощью make install и убедитесь в том, что он работает, запустив `./bin/gendiff -h` в терминале. Команду `make install` необходимо запускать из корневой директории проекта.
3. Примеры использования:
```bash
    ./bin/gendiff --format plain path/to/file.yml another/path/file.json
    ./bin/gendiff filepath1.json filepath2.json
```
[![asciicast](https://asciinema.org/a/e0KNn5H2FhBw1eytvzERYTIv6.svg)](https://asciinema.org/a/e0KNn5H2FhBw1eytvzERYTIv6)
[![asciicast](https://asciinema.org/a/c1JWMBXHjJw81eSUeJsDsaUaT.svg)](https://asciinema.org/a/c1JWMBXHjJw81eSUeJsDsaUaT)
[![asciicast](https://asciinema.org/a/B8FyWTxxt6hVCBpHONg8tDmvz.svg)](https://asciinema.org/a/B8FyWTxxt6hVCBpHONg8tDmvz)
[![asciicast](https://asciinema.org/a/PSBEKSr4Ns8MdOhs1DowdDDuN.svg)](https://asciinema.org/a/PSBEKSr4Ns8MdOhs1DowdDDuN)
[![asciicast](https://asciinema.org/a/qU9Un8J4vOJ80FKecRtPwzMTX.svg)](https://asciinema.org/a/qU9Un8J4vOJ80FKecRtPwzMTX)