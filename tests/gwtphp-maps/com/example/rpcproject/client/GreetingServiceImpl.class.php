<?php
class GreetingServiceImpl {
    
    public function greetServer($input){
        return "Ack:" . $input;
    }
    
    public function getSumLong($a, $b){
        return $a->longValue() + $b->longValue();
    }
    
    public function doError(){
        throw new IllegalArgumentException("Java system exception");
    }
}
