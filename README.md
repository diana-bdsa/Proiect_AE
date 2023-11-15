# Proiect_AE_DC
 Diana Caragheorghiev
Instructiuni de instalare:

1. Descarca PHP de pe https://windows.php.net/downloads/releases/php-8.2.12-nts-Win32-vs16-x64.zip si pune-l in C:\php
2. Copiaza din C:\php php.ini-development in acelasi folder si numeste-l php.ini
In fisierul php.ini decomenteaza liniile:
extension=curl
extension=fileinfo
extension=intl
extension=mbstring
extension=openssl
extension=pdo_mysql
(aflate pe la randul ~925)
Si extension=zip
(aflat pe randul ~961)

3. Descarca Symfony binaries de aici https://github.com/symfony-cli/symfony-cli/releases/download/v5.7.3/symfony-cli_windows_386.zip
Dezarhiveaza continutul si pune-l in C:\symfony

4. Creeaza 2 variabile de sistem in PATH
Una catre C:\php
Cealalta catre C:\symfony

5. Descarca Composer de pe https://getcomposer.org/Composer-Setup.exe si instaleaza-l

6. Descarca MySQL server de aici https://dev.mysql.com/downloads/file/?id=523158
Pe parcursul instalarii seteaza userul root si parola tot root

7. Cloneaza repository-ul undeva
Deschide un terminal in folderul proiectului
scrie in terminal "composer install" si asteapta install-ul
scrie in terminal "doctrine:database:create" si asteapta crearea bazei de date
scrie in terminal "doctrine:migrations:migrate" si asteapta migrarea bazei de date

8. Scrie in terminal "symfony server:start"

9. Website-ul poate fi accesat acum de pe link-ul
    http://127.0.0.1:8000/
   sau
    http://localhost:8000/

Nota: Pentru utilizatorul de tip Admin, dupa creerea utilizatorului trebuie sa editati manual in baza de date la campul user.roles cu urmatorul query:

UPDATE ecom.user t
SET t.roles = '["ROLE_ADMIN"]'
WHERE t.id = 1;
*unde id este id-ul utilizatorul creeat care primeste rol de admin

Configurarea si administrarea se acceseaza din http://localhost:8000/admin
