<?php

declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;
use DateTimeZone;
use JsonException;
use RuntimeException;

class Logger
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    private const TIMEZONE = 'UTC';

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }

    private static function log(string $level, string $message, array $context): void
    {
        $timestamp = (new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE)))
            ->format(self::DATE_FORMAT);

        $contextString = '';
        if (!empty($context)) {
            try {
                $contextString = ' ' . json_encode(
                    $context,
                    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
                );
            } catch (JsonException $e) {
                $contextString = ' {"context_error":"Invalid context data"}';
            }
        }

        $logEntry = sprintf(
            "[%s] [%s] %s%s%s",
            $timestamp,
            $level,
            $message,
            $contextString,
            PHP_EOL
        );

        if (!defined('LOG_PATH')) {
            throw new RuntimeException('LOG_PATH constant is not defined.');
        }

        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0755, true);
        }

        $filename = 'app-' . date('Y-m-d') . '.log';
        $logFile = LOG_PATH . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
