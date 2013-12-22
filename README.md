Falcon
========================   
Falcon is built on top of Zend Framework 2.x. Main source is separate from MVC. It uses PHPUnit testing framework.

### Setup

- Apache 2.2
- PHP 5.3+
- MySQL 5.x (5.5 is preferred)
- Composer (required to download external deps)

You may want to setup a virtual host for the site. Sample virtual host configuration can be found under _<projectpath>build/httpd/vhost.conf_. Once you clone this repo into your environment, besides setting up your web folder, you need to issue `composer update` from root directory, and execute SQL files under _build/schema_.