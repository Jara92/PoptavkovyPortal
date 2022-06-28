# Poptejsi.cz
Autor: Jaroslav Fikar (fikarja3@fit.cvut.cz)

Je portál zabývající se evidencí poptávek a zprostředkováním obchodu mezi zákazníky a podnikateli. 

## Informace o aplikaci
Aplikace je implementována v PHP pomocí frameworku Symfony. Frontendová část aplikace je postavena na Bootstrap 5 a jQuery. 

## Běhové prostředí
Jako běhové prostředí aplikace byl zvolen VPS server se systémem Ubuntu. Konkrétní nástroje (LAMP, Emailový server, nástroj, Supervisor...) jsou blíže popsány v práci.

## Požadavky
- PHP 8.1
- Symfony 6
- nástroj Composer
- nástroj NPM
- webový server Apache
- databáze MySQL

## Instalace
1. Zkopírování zdrojových souborů aplikace
2. Vytvoření databáze a její konfigurace v souboru `.env`
3. Instalace PHP závislostí `composer install`
4. Instalace databáze `php bin/console doctrine:database:create`
5. Aktualizace schématu databáze `php bin/console doctrine:schema:update`
6. Předpřipravené data je možné do databáze načíst příkazem `php bin/console doctrine:fixtures:load`
7. Nastavení emailů v souboru `.env`
8. Sestavení statických souborů `npm run build` nebo `npm run dev`
9. Je nutné zařídit, aby na pozadí neustále běžel program `php bin/console messenger:consume`
10. Pro spuštění automatických úloh `app:inquiry-newsletter` a `app:inquiry-remover` je nutné nastavit Cron úlohy ideálně na každou hodinu.
