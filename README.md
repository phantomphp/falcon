Falcon
========================   
Falcon is built on top of Zend Framework 2.x, though some effort was done to keep main source separate from MVC. It uses PHPUnit testing framework, mainly for the main source code, not the ZF.

Development
------------   
For consistency, I recommend using below instructions to setup a development environment. You are free, of course, to use whatever you want - heck, I even dare you to use IIS...Final code is what matters. If you choose otherwise, WAMP/XAMPP should serve as well. Just clone the repo, run `composer update`, and apply Apache rewrite rules found under _build/httpd_

### Setup

- Apache 2.2
- PHP 5.3+
- MySQL 5.x (5.5 is preferred)
- Composer (required to download external deps)

### Development environment
Production environment is built [currently] on Linux (RHEL 6). Using tools below you can help replicate that environment, unless you are already using Linux distro. Even then, I urge you to try Vagrant to use disposable environment - and keep your system clean. 

[VirtualBox](https://www.virtualbox.org/ "VirtualBox")  
VirtualBox is required for Vagrant. 

[Vagrant](http://vagrantup.com)  
Install Vagrant. It should take you 30 minutes to learn Vagrant - it's that easy. Given you know what a virtual machine is.

Part of setup uses pre-set VM image used to create a replica using Vagrant. It's a 450M file you can [download here](http:://dropbox.link.goes.here.net). But you dont have to - you can create a base box from scratch. Contact me and I will tell you how. Or Google it ;).

### Prepare project directory
The Falcon project itself will reside within the VM. The only thing you need to download before you clone the repository is the Vagrantfile. It is the config file used by Vagrant to setup a VM.  
So create a directory anywhere in your system, and place the Vagrantfile there - by downloading it from the Falcon Github repo. Then using your favorite shell, run:

```
shell> cd <project dir>
shell> vagrant up
```

This will kick of VM setup stage which should take few minutes to complete.



...to be completed
