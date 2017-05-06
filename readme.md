# Events fire
Is simple observable events for php

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/46d2a96bb25c48c6bca68629b9544174)](https://www.codacy.com/app/carlosocarvalho-git/php-event?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=carlosocarvalho/php-event&amp;utm_campaign=Badge_Grade) [![Latest Stable Version](https://poser.pugx.org/carlosocarvalho/event/v/stable)](https://packagist.org/packages/carlosocarvalho/event) [![Total Downloads](https://poser.pugx.org/carlosocarvalho/event/downloads)](https://packagist.org/packages/carlosocarvalho/event) [![Latest Unstable Version](https://poser.pugx.org/carlosocarvalho/event/v/unstable)](https://packagist.org/packages/carlosocarvalho/event) [![License](https://poser.pugx.org/carlosocarvalho/event/license)](https://packagist.org/packages/carlosocarvalho/event) 

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
