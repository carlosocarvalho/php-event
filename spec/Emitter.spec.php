<?php 

use \COC\Event\Emitter;
use Kahlan\Plugin\Double;

class FakeSubscriber implements \COC\Event\SubscribeContract{
     
          public function getEvents(){
                  return [
                          'em.createdUser'  => 'onNewUser',
                          'em.createdEvent' => 'onNewEvent'
                  ];
          }
}

describe( Emitter::class,  function(){
    
    beforeEach( function(){
         
         $reflection = new ReflectionClass(Emitter::class);

         $instance = $reflection->getProperty('__instance');
         $instance->setAccessible(true);
         $instance->setValue(null, null);
         $instance->setAccessible(false);

    });
    given('emitter', function(){ return Emitter::getInstance();});

    it( 'should singleton instance class ', function(){
           $instance = Emitter::getInstance();
           expect($instance)->toBeAnInstanceOf(Emitter::class );
           expect($instance)->toBe( Emitter::getInstance() );

    });
    describe('::on',function(){
           
           it( ' should trigger the listened event', function(){
                    
                    $calls = [];
                    $this->emitter->on('em.created', function() use( & $calls){
                        $calls[] = 2;
                    });
                    expect(count($calls))->toBe(0);
                    $this->emitter->emit('em.created');
                    expect(count($calls))->toBe(1);   

            });


            it( ' should trigger the listened event wiht double', function(){
                    
                    $double = Double::instance();

                    $data = ['name'=>'Carlos'];
                  
                    expect( $double)->toReceive('onNewUser')->times(1)->with($data);

                    $this->emitter->on('em.created',[$double, 'onNewUser'])->once();
                    $this->emitter->emit('em.created', $data);
                    $this->emitter->emit('em.created', $data);

            });

            it('should trigger the listerner in order', function(){

                 $double = Double::instance();

                 $data = ['name'=>'Carlos'];

                 expect($double)->toReceive('onNewUserB')->once()->ordered;
                 expect($double)->toReceive('onNewUserA')->once()->ordered;


                 $this->emitter->on('em.createdUser',[ $double,'onNewUserA'],1);
                 $this->emitter->on('em.createdUser',[ $double,'onNewUserB'], 200);
                 $this->emitter->emit('em.createdUser', $data);
            });



           
    });
    describe('::once', function(){

           
            it('should trigger event once', function(){
                  $double = Double::instance();

                 $data = ['name'=>'Carlos'];
                 expect($double)->toReceive('onNewUser')->once();
                 $this->emitter->once('em.createdUser',[ $double,'onNewUser'],1);
                 $this->emitter->emit('em.createdUser', $data);


            });
    });
    describe('::stopPropagation',function(){
           
           it( ' should stop next event', function(){
                     
                     
                 $double = Double::instance();

                 $data = ['name'=>'Carlos'];

                 expect($double)->toReceive('onNewUserB')->once()->ordered;
                 expect($double)->toReceive('onNewUserA')->once()->ordered;


                 $this->emitter->on('em.createdUser',[ $double,'onNewUserA'],1)->stopPropagation();
                 $this->emitter->on('em.createdUser',[ $double,'onNewUserB'], 200);
                 $this->emitter->emit('em.createdUser', $data);
                    
                   

            });


            it( ' should prevent event', function(){
                     
                     
                 $double = Double::instance();

                 $data = ['name'=>'Carlos'];

                 $closure =  function() use( $double) {
                       $this->emitter->on('em.createdUser',[ $double,'onNewUser'],1);
                       $this->emitter->on('em.createdUser',[ $double,'onNewUser'], 2);
                  
                 };


                 expect( $closure )->toThrow( new \COC\Event\DoubleEventException() );

                 
                   

            });

    });       
   
    describe('::addSubscribe', function() {


            it('should trigger every events subscriber', function(){
                        $subscriber = Double::instance([
                                'extends'=> FakeSubscriber::class,
                                'methods'=>['onNewUser','onNewEvent']
                                ]);
                        $data = ['name'=>'Carlos'];
                        expect($subscriber)->toReceive('onNewUser')->times(2)->with($data);
                        expect($subscriber)->toReceive('onNewEvent')->times(1)->with([10,20,30]);
                        
                        $this->emitter->addSubscriber( $subscriber );
                        $this->emitter->emit('em.createdUser', $data);
                        $this->emitter->emit('em.createdUser', $data);
                        $this->emitter->emit('em.createdEvent', [10,20,30]);
                        
                         
                    

            });
    });
    
});