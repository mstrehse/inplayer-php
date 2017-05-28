<?php
namespace Inplayer;

interface SSOInterface{
  public function login($username, $password);
  public function logout();
  public function getUser();
}
