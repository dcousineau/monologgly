<?php

/*
 * This file is part of the monologgly package.
 *
 * (c) Jose Prado <cowlby@me.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cowlby\Monolog\Handler;

use Cowlby\Loggly\LogglyInterface;
use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Logs to the Loggly service using the specified Loggly interface.
 *
 * @author Jose Prado <cowlby@me.com>
 * @package monologgly
 */
class LogglyHandler extends AbstractProcessingHandler
{
    protected $loggly;

    /**
     * Constructor.
     *
     * @param LogglyInterface $loggly The LogglyInterface to log to.
     * @param integer $level The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(LogglyInterface $loggly, $level = Logger::DEBUG, $bubble = true)
    {
        $this->setLoggly($loggly);

        parent::__construct($level, $bubble);
    }

    /**
     * Gets the Loggly interface object.
     *
     * @return LogglyInterface
     */
    public function getLoggly()
    {
        return $this->loggly;
    }

    /**
     * Sets the Loggly interface to use.
     *
     * @param LogglyInterface $loggly
     * @return LogglyHandler
     */
    public function setLoggly(LogglyInterface $loggly)
    {
        $this->loggly = $loggly;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        return $this->loggly->send($record['formatted']);
    }

    /**
     * {@inheritdoc}
     *
     * Depending on the input type of the Loggly interface, this will default
     * to either a JSON formatter or a text appropriate formatter.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        if ($this->getLoggly()->getInput()->getFormat() === 'json') {
            return new JsonFormatter;
        }

        return parent::getDefaultFormatter();
    }
}
