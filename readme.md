# Events fire
Is simple observable events for php

## Install
```php 
 composer require carlosocarvalho/event
```

## usage

```php
<?php

$emitter = \COC\Event\Emitter::getInstance();

$emitter->on('evm.createdUser', function($name, $lastname){
    print("hello {$lastname}, {$name} welcome!!");
});

$emitter->emit('evm.createdUser','Firstname','Lastname');
``` 
