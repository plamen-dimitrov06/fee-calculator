<?php

namespace FeeCalculator\Logging;

/**
 * This class simulates logging to a database
 * or an external service (ELK, DataDog, Sentry)
 */
class CliLogger
{
    public function debug(string $message, array $context = array()): void {
        $message = isset($context['exception'])
            ? $context['exception']->getMessage()
            : $message;
        echo 'Debug : ' . $message . PHP_EOL;
    }

    public function error(string $message, array $context = array()): void {
        $message = isset($context['exception'])
            ? $context['exception']->getMessage()
            : $message;
        echo 'Error : ' . $message . PHP_EOL;
    }
}