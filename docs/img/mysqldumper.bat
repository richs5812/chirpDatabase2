FOR /f %%a IN ('WMIC OS GET LocalDateTime ^| FIND "."') DO SET DTS=%%a
SET date=%DTS:~0,8%-%DTS:~8,6%

C:\xampp\mysql\bin\mysqldump.exe chirpdatabase -h localhost -u CHIRP -pNFGXTZaHG88jrEXM > "C:\Users\Brightmoor Connect\Documents\database backups\chirpdatabase-%date%.sql"
