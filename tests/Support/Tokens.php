<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rules about the tokens that a schema cannot state: the name of the CSS variable and the
 * prefix of the name follow the category, and every pair of colours that is used together reaches
 * the contrast it must, in both modes.
 */
final class Tokens
{
    /**
     * @param  array{tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    public static function violations(array $spec): array
    {
        return [
            ...self::variableNames($spec['tokens']),
            ...self::categoryPrefixes($spec['tokens']),
            ...self::layoutTokens($spec['tokens']),
            ...self::contrastProblems($spec),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @return list<string>
     */
    private static function variableNames(array $tokens): array
    {
        $found = [];

        foreach ($tokens as $token) {
            if ($token['css'] !== '--studeo-'.$token['name']) {
                $found[] = "token [{$token['name']}] must have the variable [--studeo-{$token['name']}], not [{$token['css']}]";
            }
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @return list<string>
     */
    private static function categoryPrefixes(array $tokens): array
    {
        $found = [];

        foreach ($tokens as $token) {
            if (! str_starts_with($token['name'], $token['category'].'-')) {
                $found[] = "token [{$token['name']}] of category [{$token['category']}] must start with [{$token['category']}-]";
            }
        }

        return $found;
    }

    /**
     * A layout value (space, size, font size, weight, leading, shadow) is a utility class of the implementation, never a token: a token is
     * a colour, a radius or a font family, the things a host brands.
     *
     * @param  list<array<string, mixed>>  $tokens
     * @return list<string>
     */
    private static function layoutTokens(array $tokens): array
    {
        $found = [];

        foreach ($tokens as $token) {
            if (preg_match('/^(color|radius|font-family)-/', $token['name']) !== 1) {
                $found[] = "token [{$token['name']}] is not a colour, a radius or a font family: a layout value is a utility class, never a token";
            }
        }

        return $found;
    }

    /**
     * @param  array{tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function contrastProblems(array $spec): array
    {
        $byName = array_column($spec['tokens'], null, 'name');
        $found = [];

        foreach ($spec['contrast'] as $pair) {
            $found = [...$found, ...self::pairProblems($pair, $byName)];
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $pair
     * @param  array<string, array<string, mixed>>  $byName
     * @return list<string>
     */
    private static function pairProblems(array $pair, array $byName): array
    {
        $label = "contrast pair [{$pair['foreground']} on {$pair['background']}]";
        $problems = [];

        foreach ([$pair['foreground'], $pair['background']] as $name) {
            $problem = self::tokenProblem($label, $name, $byName[$name] ?? null);

            if ($problem !== null) {
                $problems[] = $problem;
            }
        }

        return $problems === [] ? self::ratioProblems($pair, $byName, $label) : $problems;
    }

    /**
     * @param  array<string, mixed>|null  $token
     */
    private static function tokenProblem(string $label, string $name, ?array $token): ?string
    {
        if ($token === null) {
            return "{$label} names unknown token [{$name}]";
        }

        if ($token['category'] !== 'color') {
            return "{$label} uses token [{$name}] that is not a colour";
        }

        if (strlen($token['light']) !== 7 || strlen($token['dark']) !== 7) {
            return "{$label} uses token [{$name}] that has an alpha channel";
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $pair
     * @param  array<string, array<string, mixed>>  $byName
     * @return list<string>
     */
    private static function ratioProblems(array $pair, array $byName, string $label): array
    {
        $found = [];

        foreach (['light', 'dark'] as $mode) {
            $ratio = Contrast::ratio($byName[$pair['foreground']][$mode], $byName[$pair['background']][$mode]);

            if ($ratio < $pair['minimum']) {
                $found[] = "{$label} has a contrast of ".number_format($ratio, 2)." in the {$mode} mode, below {$pair['minimum']}";
            }
        }

        return $found;
    }
}
