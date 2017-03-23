# slimAPI

* Inspired of this tutorial http://www.bradcypert.com/building-a-restful-api-in-php-using-slim-eloquent/


1. `git clone https://github.com/techjackpot/slimAPI.git`
2. `composer install` Make sure you installed composer already.

- You need to run patients.sql on `aihealth` database. (DB configuration can be seen on src/config.php)
```
mysql -u root
>use aihealth;
>source patients.sql;
```

- In public folder, run `php -S localhost:8080`
- Use Postman to check APIs.
