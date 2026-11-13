<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The contrast ratio of two colours as WCAG 2 defines it, from two hex colours of six digits.
 */
final class Contrast
{
    public static function ratio(string $first, string $second): float
    {
        $one = self::luminance($first);
        $two = self::luminance($second);

        return (max($one, $two) + 0.05) / (min($one, $two) + 0.05);
    }

    private static function luminance(string $hex): float
    {
        [$red, $green, $blue] = array_map(fn (string $pair): int => (int) hexdec($pair), str_split(substr($hex, 1, 6), 2));

        return 0.2126 * self::linear($red) + 0.7152 * self::linear($green) + 0.0722 * self::linear($blue);
    }

    private static function linear(int $channel): float
    {
        $value = $channel / 255;

        return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
    }
}
