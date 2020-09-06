CHẠY NHỮNG SAU ĐỂ CÀI DEPLOY WEB
1. npm install
2. composer install
3. php artisan config:clear
4. php artisan cache:clear
5. npm run prod
------
6. nodejs socket.js 
-----
7. Vào file .env -> config và connect database
APP_URL=http://localhost:82
SOCKET_URL=http://localhost:3000
APP_DEBUG=false
APP_ENV=production
--------
8. Phân quyền cho đọc và ghi cho 2 folder
/public
/storage
-----
9. Restore DATABASE.sql
