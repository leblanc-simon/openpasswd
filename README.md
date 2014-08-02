OpenPasswd
==========

It's a simple tool for manage password and share with other people.
You can manage users and theirs right.

Installation
------------

* Get the project

```bash
git clone https://github.com/leblanc-simon/openpasswd.git
cd openpasswd
composer install
cp app/config.php.template app/config.php
```

* Edit the ```app/config.php``` to indicate the database connection.
* Initialize the database with ```app/Resources/db/openpasswd.sql```

Thanks to
---------

* [Silex](http://silex.sensiolabs.org) - License MIT
* [Symfony Components](http://symfony.com/components) - License MIT
* [jQuery](http://jquery.com/) - License MIT
* [Chosen](http://harvesthq.github.io/chosen/) - License MIT
* [Bootstrap](http://getbootstrap.com/) - License MIT
* [Jquery-templating](http://codepb.github.io/jquery-template/) - License MIT

Author
------

Simon Leblanc <contact@leblanc-simon.eu>


License
-------

[MIT](http://opensource.org/licenses/MIT)