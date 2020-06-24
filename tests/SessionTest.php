<?php

namespace Lucario\Tests;

use Lucario\Session;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class SessionTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $session = new Session();
        $session->set('test1', 123);
        $this->assertSame(123, $session->get('test1'));
    }

    public function testGetUnsetValueReturnNull(): void
    {
        $session = new Session();
        $this->assertNull($session->get('test2'));
    }

    public function testSetAndDelete(): void
    {
        $session = new Session();
        $session->set('test3', 123)->delete('test3');
        $this->assertNull($session->get('test3'));
    }

    public function testCanDeleteUnsetValue(): void
    {
        $session = new Session();
        $session->delete('test4');
        $this->assertNull($session->get('test4'));
    }
}