<?php

namespace Mixset\SessionManager\Tests;

ob_start();

require_once 'src/Session.php';

use Mixset\SessionManager\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    private $session;

    public function setUp()
    {
        session_start();
        $this->session = new Session();

        $this->session->set([
            'foo' => '<b>bar</b>',
            'key' => 'value',
            'class' => '<i>Session</i>',
            'id' => 1,
        ], ['foo']);
    }

    public function testGetValueFromSession()
    {
        $this->assertEquals('<b>bar</b>', $this->session->get('foo'));
        $this->assertEquals('value', $this->session->get('key'));
        $this->assertEquals('Session', $this->session->get('class'));
        $this->assertEquals(1, $this->session->get('id'));
    }

    public function testGetAllSessionCount()
    {
        $this->assertEquals(4, count($this->session->all()));
    }

    public function testSetOneValue()
    {
        $this->session->delete(Session::SESSION_CLEAR_VARIABLE);
        $this->session->set(['key' => 'value']);

        $this->assertEquals(1, count($this->session->all()));
        $this->assertEquals('value', $this->session->get('key'));
    }

    public function testExistsKeyInValue()
    {
        $this->assertTrue($this->session->exists('foo'));
        $this->assertTrue($this->session->exists('key'));
        $this->assertTrue($this->session->exists('class'));
        $this->assertTrue($this->session->exists('id'));
    }

    public function testDeleteType2()
    {
        $this->session->delete(Session::SESSION_CLEAR_VARIABLE);

        $this->assertEquals(0, count($this->session->all()));
    }

    public function testRemoveOne()
    {
        $this->session->removeOne('class');

        $this->assertFalse($this->session->exists('class'));
    }

    public function testExceptKeys()
    {
        $this->session->delete(Session::SESSION_CLEAR_VARIABLE);
        $this->session->clearExceptKeys();

        $this->session->setExceptKeys(['login']);
        $this->session->set([
            'login' => '<b>Test</b>',
            'email' => 'username@domain.com',
        ]);

        $this->assertEquals(11, strlen($this->session->get('login')));
        $this->assertEquals(19, strlen($this->session->get('email')));
    }

    public function tearDown()
    {
        session_destroy();
    }
}

ob_end_flush();
