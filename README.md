##session.class.php
-----------------

This is class, which provides easy way to manage sessions on your website.
 
How to install?
-----------------
To start using class, you have to include this class to your project, eg. use `include`,`require` function or `__autoload()` function. 

How is class built?
-----------------
- `getSessionId()`
- `regenerateId($type = true)`
- `get($key)`
- `all()`
- `set($data = [])`
- `exists($name)`
- `remove($type = false)`
- `removeOne($key)`
- `secure($data)`

If you want to create new instance of class, you need to use this commend: `$session = new Module\Session();`

Changelog
--------
[16-08-2013] - v1.0
- First version has been released 

[01.12.2015] - v.1.1
- added namespace to not do conflict 
- some methods have been improved

[15.02.2017] - v1.2
- code optimization
- secure method added
- removed `$multi` param from `set` method

ToDo
--------
- add management of session lifetime