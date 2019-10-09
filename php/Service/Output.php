<?php

namespace Service;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;

class Output
{
    const OUTPUT_LEVEL_DEBUG    = 'debug';
    const OUTPUT_LEVEL_INFO     = 'info';
    const OUTPUT_LEVEL_NOTICE   = 'notice';
    const OUTPUT_LEVEL_WARNING  = 'warning';
    const OUTPUT_LEVEL_ERROR    = 'error';
    const OUTPUT_LEVEL_CRITICAL = 'critical';

    public static function getInstance($config, $name)
    {
        $formatter   = new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context%\n", 'Y-m-d H:i:s', false, true);
        $outputLevel = self::getOutputLevel($config);
        $handlers    = self::getOutputHandlers($config, $formatter, $outputLevel, $config['ident']);

        return new Logger($name, $handlers);
    }

    private static function getOutputLevel($config)
    {
        switch ($config['level']) {
            case self::OUTPUT_LEVEL_CRITICAL:
                return Logger::CRITICAL;
            case self::OUTPUT_LEVEL_ERROR:
                return Logger::ERROR;
            case self::OUTPUT_LEVEL_WARNING:
                return Logger::WARNING;
            case self::OUTPUT_LEVEL_NOTICE:
                return Logger::NOTICE;
            case self::OUTPUT_LEVEL_INFO:
                return Logger::INFO;
            case self::OUTPUT_LEVEL_DEBUG:
            default:
                return Logger::DEBUG;
        }
    }

    private static function getOutputHandlers($config, $formatter, $outputLevel, $ident)
    {
        $handlers = [];
        if ($config['to']['stdout']) {
            $handler = new StreamHandler('php://stdout', $outputLevel);
            array_push($handlers, $handler);
        }
        if ($config['to']['syslog']) {
            $handler = new SyslogHandler($ident, LOG_USER, $outputLevel);
            array_push($handlers, $handler);
        }
        foreach ($handlers as $handler) {
            $handler->setFormatter($formatter);
        }

        return $handlers;
    }
}
