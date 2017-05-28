<?php
declare(strict_types=1);

namespace Inplayer\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @covers SSO
 */
class SSOTest extends TestCase{

  public function testLoginLogout(){

    $sessionStorage = new \Inplayer\Tests\MockSession();
    $sso = new \Inplayer\SSO($sessionStorage);

    // check fake login
    $result = $sso->login('admin@example.com', 'password');
    $this->assertNull($result);

    // check login
    $this->assertInstanceOf(\Inplayer\User::class, $sso->login(getenv('username'), getenv('password')));
    $this->assertEquals(getenv('username'), $sso->getUser()->email);
    $this->assertTrue($sso->isLoggedIn());

    // check double login
    $this->assertInstanceOf(\Inplayer\User::class, $sso->login(getenv('username'), getenv('password')));
    $this->assertEquals(getenv('username'), $sso->getUser()->email);
    $this->assertNotNull($sso->getUser()->id);
    $this->assertNotNull($sso->getUser()->sessionId);
    $this->assertNotNull($sso->getUser()->active);
    $this->assertTrue($sso->isLoggedIn());

    // check logout
    $this->assertTrue($sso->logout());
    $this->assertFalse($sso->isLoggedIn());
    $this->assertNull($sso->getUser());
  }
}
