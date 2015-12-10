##session.class.php
-----------------

This is class, which provides easy way to manage sessions on your website.
 
How to install?
-----------------
To start using class, you have to include this class to your project, eg. use `include`,`require` function or `__autoload()` function. 

How is class built?
-----------------
- `getSessionId()`
- `regenerateId()`
- `get()`
- `set()`
- `exists()`
- `remove()`
- `removeOne()`

If you want to create new instnce of function, you need to use this commend: `$session = new Module\Session();`

Changelog
--------
[01.12.2015]
- added namespace to not do conflict 
- some methods have been improved