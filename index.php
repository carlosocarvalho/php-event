<?php 

require_once  __DIR__ . '/vendor/autoload.php';
$emitter = \COC\Event\Emitter::getInstance();

$emitter->on('evm.createdUser', function($name, $lastname){
    print("hello {$lastname}, {$name} welcome!!");
});

$emitter->emit('evm.createdUser','Carlos','Carvalho');
