How to Run Project

1. clone the project into your local PC
2. install composer
3. install a MySQL server or Xammp to use sql database
4. create a database
5. update database credentials on urs-backend/.env file
6. open your preferred CLI and run following commands

---Install thirdparty libraries---

8. composer install

---Migration and seeding---

9. php artisan migrate:fresh --seed

---Run project---

10. php artisan serve
(Backend URL will be http://localhost:8000/)

---Run Tests---

11. ./vendor/bin/phpunit --testdox

