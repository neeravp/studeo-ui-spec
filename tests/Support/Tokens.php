<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rules about the tokens that a schema cannot state: the name of the CSS variable and the
 * prefix of the name follow the category, a host sets exactly the seven input colours, every other
 * colour is derived by a rule that reads only tokens and knobs, holds no literal colour and has no
 * cycle, every knob is read, and every pair of colours that is used together names colours that
 * take part in a contrast. That the pairs reach their contrast is not proved here: a rule is CSS,
 * and the browser tests of each UI package read what the engines compute and prove it.
 */
final class Tokens
{
    /** The colours that a host sets: everything else is derived from them. */
    public const array INPUTS = [
        'color-background',
        'color-text',
        'color-accent',
        'color-danger',
        'color-success',
        'color-warning',
        'color-info',
    ];

    /**
     * @param  array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    public static function violations(array $spec): array
    {
        return [
            ...self::variableNames($spec['tokens']),
            ...self::categoryPrefixes($spec['tokens']),
            ...self::layoutTokens($spec['tokens']),
            ...self::inputs($spec['tokens']),
            ...self::rules($spec['tokens'], $spec['knobs']),
            ...self::cycles($spec['tokens']),
            ...self::unreadKnobs($spec['tokens'], $spec['knobs']),
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
     * @param  list<array<string, mixed>>  $tokens
     * @return list<string>
     */
    private static function inputs(array $tokens): array
    {
        $found = array_column(array_filter($tokens, fn (array $token): bool => ($token['kind'] ?? null) === 'input'), 'name');

        if ($found === self::INPUTS) {
            return [];
        }

        return ['the inputs must be ['.implode(', ', self::INPUTS).'] in this order, not ['.implode(', ', $found).']'];
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @param  list<array<string, mixed>>  $knobs
     * @return list<string>
     */
    private static function rules(array $tokens, array $knobs): array
    {
        $known = [...array_column($tokens, 'css'), ...array_column($knobs, 'css')];
        $found = [];

        foreach (self::derived($tokens) as $token) {
            $found = [...$found, ...self::unknownVariables($token, $known), ...self::literalColours($token)];
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @return list<array<string, mixed>>
     */
    private static function derived(array $tokens): array
    {
        return array_values(array_filter($tokens, fn (array $token): bool => ($token['kind'] ?? null) === 'derived'));
    }

    /**
     * @return list<string>
     */
    private static function variablesRead(string $rule): array
    {
        preg_match_all('/var\((--[a-z0-9-]+)\)/', $rule, $matches);

        return $matches[1];
    }

    /**
     * @param  array<string, mixed>  $token
     * @param  list<string>  $known
     * @return list<string>
     */
    private static function unknownVariables(array $token, array $known): array
    {
        $found = [];

        foreach (array_diff(self::variablesRead((string) ($token['derive'] ?? '')), $known) as $variable) {
            $found[] = "the rule of token [{$token['name']}] reads [{$variable}], which is neither a token nor a knob";
        }

        return $found;
    }

    /**
     * A rule holds no colour of its own: a hex value, or a colour function that does not start from another colour.
     *
     * @param  array<string, mixed>  $token
     * @return list<string>
     */
    private static function literalColours(array $token): array
    {
        $literal = '/#[0-9a-f]{3,8}\b|(?<![a-z-])(?:rgba?|hsla?|hwb|lab|lch|oklab|oklch|color)\((?!\s*from\b)/i';

        if (preg_match($literal, (string) ($token['derive'] ?? '')) !== 1) {
            return [];
        }

        return ["the rule of token [{$token['name']}] holds a literal colour: a rule reads tokens and knobs only"];
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @return list<string>
     */
    private static function cycles(array $tokens): array
    {
        $reads = [];

        foreach (self::derived($tokens) as $token) {
            $reads[$token['css']] = self::variablesRead((string) ($token['derive'] ?? ''));
        }

        $found = [];

        foreach (array_keys($reads) as $css) {
            if (self::reaches($reads, $css, $css, [])) {
                $found[] = "the rule of [{$css}] reads itself, directly or through other rules";
            }
        }

        return $found;
    }

    /**
     * @param  array<string, list<string>>  $reads
     * @param  list<string>  $visited
     */
    private static function reaches(array $reads, string $from, string $target, array $visited): bool
    {
        foreach ($reads[$from] ?? [] as $next) {
            if ($next === $target) {
                return true;
            }

            if (! in_array($next, $visited, true) && self::reaches($reads, $next, $target, [...$visited, $next])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array<string, mixed>>  $tokens
     * @param  list<array<string, mixed>>  $knobs
     * @return list<string>
     */
    private static function unreadKnobs(array $tokens, array $knobs): array
    {
        $read = [];

        foreach (self::derived($tokens) as $token) {
            $read = [...$read, ...self::variablesRead((string) ($token['derive'] ?? ''))];
        }

        $found = [];

        foreach (array_diff(array_column($knobs, 'css'), $read) as $css) {
            $found[] = "knob [{$css}] is read by no rule";
        }

        return $found;
    }

    /**
     * @param  array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function contrastProblems(array $spec): array
    {
        $byName = array_column($spec['tokens'], null, 'name');
        $found = [];

        foreach ($spec['contrast'] as $pair) {
            $label = "contrast pair [{$pair['foreground']} on {$pair['background']}]";

            foreach ([$pair['foreground'], $pair['background']] as $name) {
                $problem = self::tokenProblem($label, $name, $byName[$name] ?? null);

                if ($problem !== null) {
                    $found[] = $problem;
                }
            }
        }

        return $found;
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

        if (($token['kind'] ?? null) === 'constant') {
            return "{$label} uses token [{$name}] that is a constant and takes part in no contrast";
        }

        return null;
    }
}
