<?php

namespace Cowlby\Monolog\Handler;

use Cowlby\Loggly\LogglyInterface;
use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class LogglyHandler extends AbstractProcessingHandler
{
    protected $loggly;

    public function __construct(LogglyInterface $loggly, $level = Logger::DEBUG, $bubble = true)
    {
        $this->setLoggly($loggly);

        parent::__construct($level, $bubble);
    }

    public function getLoggly()
    {
        return $this->loggly;
    }

    public function setLoggly(LogglyInterface $loggly)
    {
        $this->loggly = $loggly;
        return $this;
    }

    protected function write(array $record)
    {
        return $this->loggly->send($record['formatted']);
    }

    protected function getDefaultFormatter()
    {
        if ($this->getLoggly()->getInput()->getFormat() === 'json') {
            return new JsonFormatter;
        }

        return parent::getDefaultFormatter();
    }
}
