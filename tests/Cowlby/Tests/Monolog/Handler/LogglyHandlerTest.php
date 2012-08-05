<?php

namespace Cowlby\Tests\Monolog\Handler;

use Cowlby\Monolog\Handler\LogglyHandler;

class LogglyHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $loggly;
    private $handler;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->loggly = $this->getMock('Cowlby\Loggly\LogglyInterface');
        $this->handler = new LogglyHandler($this->loggly);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->handler = null;
    }

    public function testGetSetLoggly()
    {
        $expected = 'Cowlby\Loggly\LogglyInterface';
        $this->assertInstanceOf($expected, $this->handler->getLoggly());

        $handler = $this->handler->setLoggly($this->loggly);
        $this->assertSame($this->loggly, $this->handler->getLoggly());
        $this->assertSame($this->handler, $handler, 'Fluent interface broken.');
    }

    public function testGetDefaultFormatterReturnsJsonFormatter()
    {
        $input = $this->getMock('Cowlby\\Loggly\\Input\\InputInterface');

        $input
            ->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue('json'))
        ;

        $this->loggly
            ->expects($this->once())
            ->method('getInput')
            ->will($this->returnValue($input))
        ;

        $formatter = $this->handler->getFormatter();
        $this->assertInstanceOf('Monolog\\Formatter\\JsonFormatter', $formatter);
    }

    public function testGetDefaultFormatterReturnsLineFormatter()
    {
        $input = $this->getMock('Cowlby\\Loggly\\Input\\InputInterface');

        $input
            ->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue('text'))
        ;

        $this->loggly
            ->expects($this->once())
            ->method('getInput')
            ->will($this->returnValue($input))
        ;

        $formatter = $this->handler->getFormatter();
        $this->assertInstanceOf('Monolog\\Formatter\\LineFormatter', $formatter);
    }

    public function testWrite()
    {
        $input = $this->getMock('Cowlby\\Loggly\\Input\\InputInterface');

        $input
            ->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue('json'))
        ;

        $this->loggly
            ->expects($this->once())
            ->method('getInput')
            ->will($this->returnValue($input))
        ;

        $record = array(
            'message' => 'test',
            'context' => array(),
            'level' => 100,
            'level_name' => 'DEBUG',
            'channel' => 'test',
            'datetime' => new \DateTime('2012-01-01'),
            'extra' => array(),
        );

        $this->loggly
            ->expects($this->once())
            ->method('send')
            ->with($this->handler->getFormatter()->format($record))
        ;

        $this->handler->handle($record);
    }
}
