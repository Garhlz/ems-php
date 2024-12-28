@echo off
start php -S localhost:8000
timeout /t 2
start http://localhost:8000/ems/login