<?php

namespace FeeCalculator\Exceptions;

class ConversionException extends \Exception
{
    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}