<?php
namespace Inplayer;

class AuraSession implements \Inplayer\SessionStorageInterface{

  protected $segment;

  public function __construct($lifetime = 0){
    $session_factory = new \Aura\Session\SessionFactory;
    $session = $session_factory->newInstance($_COOKIE);
    $session->setCookieParams(array('lifetime' => $lifetime));
    $this->segment = $session->getSegment('Inplayer\Session');
  }

  /**
   * Set a session value
   * @param string $key
   * @param mixed $value
   * @return void
   */
  public function set($key, $value){
    $this->segment->set($key, $value);
  }

  /**
   * Get a session value
   * @param string $key
   * @return mixed
   */
  public function get($key){
    return $this->segment->get($key);
  }

  /**
   * Remove a session value
   * @param string $key
   */
  public function remove($key){
    $this->segment->remove($key);
  }

  /**
   * Check if a value exists
   * @param string $key
   */
  public function has($key){
    return $this->segment->has($key);
  }

  /**
   * Destroy the session
   * @return void
   */
  public function destroy(){
    $this->segment->destroy();
  }

}
