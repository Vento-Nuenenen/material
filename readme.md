#### Excalibour is a Build of Laravel 5.5 Exer-Management-Tool for Scouts.

[![Build Status](https://travis-ci.org/Chronyms/Excalibour.svg?branch=release)](https://travis-ci.org/Chronyms/Excalibour)
[![StyleCI](https://github.styleci.io/repos/116980577/shield?branch=release)](https://github.styleci.io/repos/116980577)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Chronyms/Excalibour/badges/quality-score.png?b=V3)](https://scrutinizer-ci.com/g/Chronyms/Excalibour/?branch=V3)
[![Build Status](https://scrutinizer-ci.com/g/Chronyms/Excalibour/badges/build.png?b=V3)](https://scrutinizer-ci.com/g/Chronyms/Excalibour/build-status/V3)

### Installation Instructions
1. `git clone https://github.com/Chronyms/Excalibour.git` ausführen
2. MySQL-Datenbank für das Projekt erstellen
    * ```mysql -u root -p```, auf Vagrant: ```mysql -u homestead -psecret```
    * ```create database excalibour;```
    * ```\q```
3. From the projects root run `cp .env.example .env`
4. Configure your `.env` file
5. Run `composer update` from the projects root folder
6. From the projects root folder run `php artisan key:generate`
7. From the projects root folder run `php artisan migrate`
8. From the projects root folder run `composer dump-autoload`
9. From the projects root folder run `php artisan db:seed`

** Note ** In order for [Dropzone.js](http://www.dropzonejs.com/#configuration) to work you will need to run
```
yarn
```

#### Rebuild Front End Assets with Mix

###### Rebuilding the front end of that project is OPTIONAL and can be done using Laravel [Mix](https://laravel.com/docs/5.5/mix) which is Elixers replacement.

1. From the projects root folder run `yarn`
2. From the projects root folder run `yarn dev` or `yarn production`
  * You can watch assets with `yarn run watch`


### Seeds
1. Seeded Roles
  * Unverified - Level 0
  * User  - Level 1
  * Administrator - Level 5

2. Seeded Permissions
  * view.users
  * create.users
  * edit.users
  * delete.users

3. Seeded Users

|Email|Password|Access|
|:------------|:------------|:------------|
|user@user.com|password|User Access|
|admin@admin.com|password|Admin Access|


### Laravel Auth License
Laravel-auth is licensed under the MIT license. Enjoy!
