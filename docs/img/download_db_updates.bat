@echo off
title Download Updates
echo Downloading updates...
echo.
ping 192.0.2.2 -n 1 -w 2000 > nul
cd c:\xampp\htdocs\chirpDatabase2
call git fetch origin master
call git reset --hard FETCH_HEAD
call git clean -df
ping 192.0.2.2 -n 1 -w 2000 > nul
echo.
echo.
echo Updates downloaded. Refreshing the cache...
echo.
PATH=%PATH%;c:\xampp\bin\php\php
php bin/console cache:clear --no-warmup -e prod
php bin/console cache:warmup -e prod
ping 192.0.2.2 -n 1 -w 2000 > nul
echo.
echo Changes have been applied. Opening the database...
ping 192.0.2.2 -n 1 -w 2000 > nul
start "" http://localhost/chirpdatabase2/web/form/client
