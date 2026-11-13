<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * Finds what a stack-neutral text must not hold: the name of a framework, a template language or
 * a test tool, and a path on somebody's machine. The names that are also plain words (Blade,
 * React, Alpine, Dusk, Pest) are found only when written with a capital letter.
 */
final class Neutrality
{
    /**
     * @var list<string>
     */
    private const array ANY_CASE = ['Livewire', 'Inertia', 'Tailwind', 'Laravel', 'Playwright', 'Svelte', 'Vue'];

    /**
     * @var list<string>
     */
    private const array CAPITAL_ONLY = ['Blade', 'React', 'Alpine', 'Dusk', 'Pest'];

    /**
     * @return list<string>
     */
    public static function violations(string $name, string $text): array
    {
        $found = [];

        foreach (self::ANY_CASE as $word) {
            if (preg_match('/\b'.preg_quote($word, '/').'\b/i', $text) === 1) {
                $found[] = "{$name} names [{$word}]";
            }
        }

        foreach (self::CAPITAL_ONLY as $word) {
            if (preg_match('/\b'.preg_quote($word, '/').'\b/', $text) === 1) {
                $found[] = "{$name} names [{$word}]";
            }
        }

        return [...$found, ...self::pathProblems($name, $text)];
    }

    /**
     * @return list<string>
     */
    private static function pathProblems(string $name, string $text): array
    {
        return preg_match('#(/Users/|/home/|~/)#', $text) === 1 ? ["{$name} holds a local path"] : [];
    }
}
