HƯỚNG DẪN DEPLOY WEBSITE

1. npm install
2. composer install
3. php artisan config:clear
4. php artisan cache:clear
5. npm run prod
------
6. nodejs socket.js 
-----
7. Vào file .env -> config và connect database
<p>APP_URL=http://localhost:82</p>
<p>SOCKET_URL=http://localhost:3000</p>
<p>APP_DEBUG=false</p>
<p>APP_ENV=production</p>
--------
8. Phân quyền cho đọc và ghi cho 2 folder
/public
/storage
-----
9. Restore DATABASE.sql
