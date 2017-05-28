<?php
namespace Inplayer;

interface SessionStorageInterface{
  public function set($key, $value);
  public function get($key);
  public function remove($key);
  public function has($key);
  public function destroy();
}
