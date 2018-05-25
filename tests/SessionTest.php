<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SessionManager\Session;

ob_start();

require_once 'src/Core/Session.php';

class SessionTest extends TestCase
{
    private $session;

    public function setUp()
    {
        session_start();
        $this->session = new Session();

        $this->session->set([
            'foo' => '<b>bar</b>',
         #   'key' => 'value',
         #   'class' => 'Session',
         #   'id' => 1,
        ]);
    }

    public function testGetValueFromSession()
    {
        $this->assertEquals('bar', $this->session->get('foo'));
       # $this->assertEquals('value', $this->session->get('key'));
      #  $this->assertEquals('Session', $this->session->get('class'));
      #  $this->assertEquals(1, $this->session->get('id'));
    }

    /*public function testGetAllSessionCount()
    {
        $this->assertEquals(4, count($this->session->all()));
    }

    public function testSetOneValue()
    {
        $this->session->delete(2);
        $this->session->set(['key' => 'value']);

        $this->assertEquals(1, count($this->session->all()));
        $this->assertEquals('value', $this->session->get('key'));
    }

    public function testExistsKeyInValue()
    {
        $this->assertTrue($this->session->exists('key'));
    }

    public function testDeleteType2()
    {
        $this->session->delete(2);

        $this->assertEquals(0, count($this->session->all()));
    }

    public function testRemoveOne()
    {
        $this->session->removeOne('class');

        $this->assertFalse($this->session->exists('class'));
    }

    public function testExceptKeys()
    {
        $this->session->delete(2);

        $this->session->set([
            'login' => '<b>Test</b>',
            'email' => 'username@domain.com'
        ]);

        $this->session->setExceptKeys(['login']);

        $this->assertEquals(11, count($this->session->get('login')));
        $this->assertEquals(19, count($this->session->get('email')));
    }*/

    public function tearDown()
    {
        session_destroy();
    }
}

ob_end_flush();
