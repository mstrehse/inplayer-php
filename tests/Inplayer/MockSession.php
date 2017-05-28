<?php
namespace Inplayer\Tests;

class MockSession implements \Inplayer\SessionStorageInterface{

  protected $data;

  public function get($key){
    if(isset($this->data[$key])){
      return $this->data[$key];
    }
  }

  public function set($key, $value){
    $this->data[$key] = $value;
  }

  public function remove($key){
    if(isset($this->data[$key])){
      unset($this->data[$key]);
    }
  }

  public function destroy(){
    $this->data = [];
  }

  public function has($key){
    if(isset($this->data[$key])){
      return true;
    }
    return false;
  }
}
