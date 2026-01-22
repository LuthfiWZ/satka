@echo off
echo ============================================
echo    SATKA API dengan Ngrok
echo ============================================
echo.

echo 🚀 Menjalankan API di port 3000...
start cmd /k "node index.js"

timeout /t 3 /nobreak > nul

echo.
echo 🌐 Menjalankan Ngrok...
echo.
echo Pilih perintah ngrok:
echo 1. ngrok http 80
echo 2. ngrok http 3001
echo 3. ngrok http 55317
echo 4. ngrok http 3000 (default)
echo.
set /p choice="Pilih [1-4]: "

if "%choice%"=="1" (
    ngrok http 80 --url assuring-quail-real.ngrok-free.app
) else if "%choice%"=="2" (
    ngrok http 3001 --url assuring-quail-real.ngrok-free.app
) else if "%choice%"=="3" (
    ngrok http 55317 --url assuring-quail-real.ngrok-free.app
) else (
    ngrok http 3000 --url assuring-quail-real.ngrok-free.app
)

pause