<?php
namespace Inplayer;

use GuzzleHttp\Client;

class SSO implements SSOInterface{

  protected $session_key = 'inplayer_sid';
  protected $sid;
  protected $session;

  public function __construct(\Inplayer\SessionStorageInterface $sessionStorage){

    $this->client = new Client([
      'base_uri' => 'https://api.invideous.com/plugin/',
      'timeout'  => 4.0,
    ]);

    $this->session = $sessionStorage;
    $this->sid = $this->session->get($this->session_key);
  }

  /**
   * Login a user, returns the response from the api
   * @param string $username
   * @param string $password
   * @param boolean $remember
   * @return array
   */
  public function login($username, $password, $remember = true){

    if($this->sid){
      return $this->getUser();
    }

    $response = $this->client->request('POST', 'login', [
      'form_params' => [
          'username' => $username,
          'password' => $password,
          'platform' => 'web',
          'remember' => intval($remember)
      ]
    ]);

    $data = json_decode($response->getBody()->getContents(), true);
    $data = $data['response'];

    // check if a session id is set
    if(isset($data['result']['user_info']['session_id'])){
      $sid = $data['result']['user_info']['session_id'];
      $this->session->set($this->session_key, $sid);
      $this->sid = $sid;
    }

    return $this->getUser();
  }

  /**
   * Logout the current user
   * @return void
   */
  public function logout(){
    $response = $this->client->request('GET', 'logout', [
      'query' => ['session_id' => $this->sid]
    ]);

    $this->destroySession();
    return true;
  }

  protected function destroySession(){
    $this->session->destroy();
    $this->sid = null;
  }

  /**
   * Returns true, if the user is logged in
   * @return boolean
   */
  public function isLoggedIn(){
    if($this->sid){
      return true;
    }
    return false;
  }

  /**
   * Get the current user
   * @return \Inplayer\User
   */
  public function getUser(){
    if(!$this->sid){
      return null;
    }

    $user = $this->fetchUser();

    if(!$user){
      $this->destroySession();
    }

    return $user;
  }

  /**
   * Fetches the user object from the api
   * @return \Inplayer\User
   */
  protected function fetchUser(){

    if(!$this->sid){
      throw new \Exception("No session_id found.");
    }

    $response = $this->client->request('GET', 'get_user_info', [
      'query' => ['session_id' => $this->sid]
    ]);

    $data = json_decode($response->getBody()->getContents(), true);
    $data = $data['response'];

    if(!isset($data['result']['user_info'])){
      throw new \Exception('No user found, maybe session_id is expired.');
    }

    if($data['result']['user_info']['username'] == 'guest'){
      return null;
    }

    return new \Inplayer\User($data['result']['user_info']);
  }

  /**
   * Deprivated: Get the user info array
   * @depricated
   */
  public function get_user_info(){
    $response = $this->client->request('GET', 'get_user_info', [
      'query' => ['session_id' => $this->session->get($this->session_key)]
    ]);
    $data = json_decode($response->getBody()->getContents(), true);
    $data = $data['response'];
    return $data;
  }

  /**
   * Depricated: Check if the request is an ajax request
   * @return boolean
   */
  public function is_ajax_request()
  {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
  }
}
