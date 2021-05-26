# Assignment

Brief Task:
1. Create mini aplication using Laravel Lumen where and make simple API for login, register, dan dashboard
2. Use laravel package `socialite` and connect to Github using OAuth by default
3. Store the data to mongodb (We prefer that you use MongoDB Atlas are free) that users connected to us to mongodb instead mysql.
4. Develop error handler for instance when user A has been registered and stored to the collection and retry to register again it would be showed up some validation and/or pass-thru to the next stpe.


Requirement
1. Database: MongoDB
2. Framework: Laravel Lumen
3. PHP Version: PHP7-fpm
4. Webserver: Nginx
5. Container: Docker (Not docker-compose)
6. Other components: Supervisor, Redis
7. Create the documentation how to run the application

Extra Plus:
1. If has phpunit for testing each of the functionalities.
2. Use helper as global rules.


# how to install

1. clone this repo
2. change directory to src
3. run composer install
4. copy .env.example and rename it to .env
5. change directory to root project
6. run docker build 
   ```
   docker build -t mini-application-lumen . --no-cache
   ```
7. start docker
   ```
   docker run --rm -it -p 8080:80 mini-application-lumen
   ```

8. after that if you face error 502 Bad gateway please edit file at /etc/php7/php7-fpm.d/www.conf and change some code just like below
   ```
   user = nginx
   group = nginx

   listen = /var/run/php/php7.4-fpm.sock
   ```
   uncomment and change this 3 line code
   ```
   ;listen.owner = nobody
   ;listen.group = nobody
   ;listen.mode = 0660
   ```
   to this
   ```
   listen.owner = nginx
   listen.group = nginx
   listen.mode = 0660
   ```

9. after that please restart docker with this command
   ```
   docker ps     # to get list container and find container with image name > mini-application-lumen
   docker restart <container:name>
   ```

10. then you can check in browser or postman with these url
   http://127.0.0.1:8080


* note to edit file you can use vi

for the API documentation you can check this gist below and import it to postman
https://gist.github.com/rochman25/47907494a791be2cfb57d776c65e387d

Thank you

Hopefully this could help.