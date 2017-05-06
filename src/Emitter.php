<?php 

namespace COC\Event;

class Emitter {
   
    /**
     * instance class
     *
     * @var object
     */
    private static $__instance;
   
    /**
    * has list with events
    */
    private $listeners = [];

    
    /**
     * get instance class
     * @return Event
     */
    public static function getInstance(){
         if( ! self::$__instance) self::$__instance = new self();
         return self::$__instance;
    }

    /**
     * fire event in lister
     *
     * @param string $event
     * @param array ...$args
     * @return void
     */
    public function emit(  $event, ...$args){
        if( $this->hasListerner($event)){
                 foreach( $this->listeners[$event] as $ev){
                     $ev->handle($args);
                     if( $ev->stopPropagation) break;
                 }
        }
    }

    /**
     * register on event for fire
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    public function on( $event, callable $callback, $priority = 0){
             if( ! $this->hasListerner( $event )){
                      $this->listeners[$event] = []; 
             }
             $this->validateCallableForEvent($event, $callback);
             $listener = new Listener ($callback, $priority);
             $this->listeners[$event][] = $listener;
             $this->orderByListener( $event );
             return $listener;
    }

    /**
     * subscriber on events
     *
     * @param SubscribeContract $subscriber
     * @return void
     */   
    public function addSubscriber( SubscribeContract $subscriber){
          $events = $subscriber->getEvents();
          foreach($events as $ev => $method){
              $this->on($ev,[ $subscriber, $method]);
          }
    }
    
    /**
     * exectue only once event
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    public function once($event, callable $callback, $priority = 0){

        return $this->on($event, $callback, $priority)->once();
    }
    
    /**
     * validate if exists event in listeners
     *
     * @param string $event
     * @return boolean
     */
    private function hasListerner($event){
          return array_key_exists( $event, $this->listeners);
    }

    /**
     * sort listeners events by priority 
     *
     * @param [type] $event
     * @return void
     */  
    private function orderByListener( $event){
          
          return uasort( $this->listeners[$event], function($a, $b){
                 $a->priority < $b->priority;
          });
    }
    
    /**
     * validate method for prevent default event
     *
     * @param [type] $event
     * @param callable $callable
     * @return void
     */
    private function validateCallableForEvent( $event,   callable $callable){
               foreach($this->listeners[$event] as $ev){
                       if($ev->callback === $callable)
                           throw new DoubleEventException();
               }
        return false;
    }
}