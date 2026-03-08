<?php

namespace SoloSearch\Logger\Model;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use SoloSearch\Core\Model\Config;

class Logger
{
    /**
     * @var MonologLogger
     */
    protected MonologLogger $logger;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $appPath = $config->get('app/path');
        $logPath = $config->get('app/logger/path') ?: 'var/log/app.log';
        $logFile = $appPath . '/' . ltrim($logPath, '/');

        $this->logger = new MonologLogger('solosearch');
        $this->logger->pushHandler(new StreamHandler($logFile, MonologLogger::DEBUG));
    }

    /**
     * Log info message
     * 
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    /**
     * Log error message
     * 
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    /**
     * Log debug message
     * 
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Log warning message
     * 
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context = [])
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Log notice message
     * 
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context = [])
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Log critical message
     * 
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context = [])
    {
        $this->logger->critical($message, $context);
    }
}
