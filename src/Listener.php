<?php 


namespace COC\Event;

class Listener {
      
    /**
     * @var callable
     */  
    public $callback;
     
    /**
     *
     * @var int
     */
    public $priority;

      /**
       * Runner listener only one
       *
       * @var boolean
       */
    private $once = false; 

    /**
     * @var int
     */  
    private $calleds = 0;
     
    /**
     * @var boolean
     */
    public $stopPropagation = false;

    public function __construct( callable $callback, $priority ){

        $this->callback = $callback;
        $this->priority = $priority;
    }
    
    /**
     * execute callback handle
     *
     * @param array $args
     * @return void
     */
    public function handle( array $args){
            
            if($this->once && $this->calleds > 0) return null; 
             
            $this->calleds++; 
            return call_user_func_array( $this->callback, $args);

    }

   
   /**
    * validate for runner callback only one 
    *
    * @return Listener
    */
   public function once(){
       $this->once = true;
       return $this;
   }

   /**
    * stop runner callbacks
    *
    * @return void
    */
   public function stopPropagation(){
         $this->stopPropagation =  true;
       return $this;
   }
}