## Как все это собрать?

*   Для начала понадобятся исходники проекта, которые можно скачать на
<https://github.com/Ksnk/Backup-script>

*   build.xml - файл для PHING'а - PHP-шного аналога ant'а. Проблема в полной несовместимости
задач ant'a и PHING'а, так что внешняя похожесть продуктов может помешать в жизни. Хотя
идеология у них примерно одинаковая. PHING- pear'овский пакет.

*   В задаче сборки используется утилита preprocessor, написанная мной. Ее вариант для PHING
находится в каталоге utils проекта. Ее следует разместить в каталоге
phing/tasks/ext/preprocessor, чтобы не портить пути в build.xml

Для запуска тестов используется phpUnit - pear'овский пакет

## Информация по файлам проекта и их смыслу

*   build/allinone/backup.php - готовый скрипт бякапа,
*   build/cms-plugin/backup.php - только класс и класс exception'ов для вставки в виде отдельного модуля.
*   test/* - каталог с данными и тестами на класс
*   utils/phing.preprocessor.zip - 4 файла утилиты preprocessor
*   src/backup.php - исходник класса BACKUP
*   src/BackupException.php - исходник класса BackupException
*   src/empty_executor.php - заголовочный файл для сборки "cms-plugin"
*   src/URI_executer.php - заголовочный файл для сборки "allinone"
*   src/main.html - шаблон главной страницы приложения для портирования их в URI_executer
*   src/progress.html - шаблоны страницы выдачи результата для портирования их в URI_executer
*   build.xml - файл сборки PHING'а
*   config.xml - файл конфигурации preprocessor
*   readme.markdown - readme? а также исходник первой страницы wiki проекта




