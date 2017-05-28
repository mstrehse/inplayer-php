<?php
namespace Inplayer;

class User{
  public $active;
  public $id;
  public $username;
  public $password;
  public $sessionId;
  public $email;
  public $currency;
  public $balance;
  public $country_code;
  public $roles;

  /**
   * Constructor with data attribute
   */
  public function __construct($data = []){

    if(isset($data['active'])){
      $this->active = (boolean)$data['active'];
    }

    if(isset($data['id'])){
      $this->id = intval($data['id']);
    }

    if(isset($data['username'])){
      $this->username = $data['username'];
    }

    if(isset($data['session_id'])){
      $this->sessionId = $data['session_id'];
    }

    if(isset($data['email'])){
      $this->email = $data['email'];
    }

    if(isset($data['currency'])){
      $this->currency = $data['currency'];
    }

    if(isset($data['balance'])){
      $this->balance = $data['balance'];
    }

    if(isset($data['country_code'])){
      $this->country_code = $data['country_code'];
    }

    if(isset($data['roles'])){
      $this->roles = $data['roles'];
    }
  }

  /**
   * Returns the user data as array
   * @return array
   */
  public function toArray(){
    return [
      'active' => $this->active,
      'id' => $this->id,
      'username' => $this->username,
      'session_id' => $this->sessionId,
      'email' => $this->email,
      'balance' => $this->balance,
      'currency' => $this->currency,
      'country_code' => $this->country_code,
      'roles' => $this->roles
    ];
  }
}
