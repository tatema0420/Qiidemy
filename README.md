# Qiidemy
 
A Web Application that Extracts Udemy URLs from Qiita Posts and Displays Popular Videos.
 
# DEMO
This site extracts Udemy URLs from articles posted on Qiita and displays the top 20 most popular videos in a ranking format.
![](http://tateblg.com/img/qiidemy.png)
 
# Features
 
In the future, we would like to monetize this site by having users register for Udemy through affiliates.
 
# Installation
 
```bash
docker-compose build
docker-compose up -d
```
 
# Usage

The following command will INSERT the Qiita article data and the Udemy video information listed in it into the database.
```bash
php artisan command:qiita_udemy
```
 
# Note

Execute ddl.sql on the MySQL server.
 
For the database information and Qiita_API items in the env file, please refer to the env_sample and register them according to your environment.

In a local environment, use docker.

In a test or production environment, deploy only the files under src.
 
# Author
 
* tatemaru

