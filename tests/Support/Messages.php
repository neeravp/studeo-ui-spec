<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rules about the words that components write themselves and the variables that they set for their own classes: a key is declared once,
 * every message is written by exactly one component and a component writes only messages that exist, the holes of a text are declared and
 * the form for one has the same holes, and a variable is declared once and is not the variable of a token.
 *
 * @phpstan-type Spec array{messages: list<array<string, mixed>>, components: list<array<string, mixed>>, tokens: list<array<string, mixed>>}
 */
final class Messages
{
    /**
     * @param  Spec  $spec
     * @return list<string>
     */
    public static function violations(array $spec): array
    {
        return [
            ...self::repeatedKeys($spec['messages']),
            ...self::unknownKeys($spec),
            ...self::claimProblems($spec),
            ...self::placeholderProblems($spec['messages']),
            ...self::variableProblems($spec),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $messages
     * @return list<string>
     */
    private static function repeatedKeys(array $messages): array
    {
        $found = [];

        foreach (array_count_values(array_column($messages, 'key')) as $key => $count) {
            if ($count > 1) {
                $found[] = "message [{$key}] is declared twice";
            }
        }

        return $found;
    }

    /**
     * @param  Spec  $spec
     * @return list<string>
     */
    private static function unknownKeys(array $spec): array
    {
        $known = array_column($spec['messages'], 'key');
        $found = [];

        foreach ($spec['components'] as $component) {
            foreach (array_diff($component['messages'] ?? [], $known) as $key) {
                $found[] = "component [{$component['name']}] writes unknown message [{$key}]";
            }
        }

        return $found;
    }

    /**
     * @param  Spec  $spec
     * @return list<string>
     */
    private static function claimProblems(array $spec): array
    {
        $writers = [];
        $found = [];

        foreach ($spec['components'] as $component) {
            foreach ($component['messages'] ?? [] as $key) {
                $writers[$key][] = $component['name'];
            }
        }

        foreach (array_column($spec['messages'], 'key') as $key) {
            $found = [...$found, ...self::claimOf($key, count($writers[$key] ?? []))];
        }

        return $found;
    }

    /**
     * @return list<string>
     */
    private static function claimOf(string $key, int $writers): array
    {
        return match (true) {
            $writers === 0 => ["message [{$key}] is written by no component"],
            $writers > 1 => ["message [{$key}] is written by more than one component"],
            default => [],
        };
    }

    /**
     * @param  list<array<string, mixed>>  $messages
     * @return list<string>
     */
    private static function placeholderProblems(array $messages): array
    {
        $found = [];

        foreach ($messages as $message) {
            $declared = $message['placeholders'] ?? [];
            $used = self::holesIn($message['text']);

            foreach (array_diff($used, $declared) as $hole) {
                $found[] = "message [{$message['key']}] uses the placeholder [:{$hole}] in its text and does not declare it";
            }

            foreach (array_diff($declared, $used) as $hole) {
                $found[] = "message [{$message['key']}] declares the placeholder [{$hole}] that its text does not use";
            }

            $found = [...$found, ...self::pluralProblems($message, $used, $declared)];
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $message
     * @param  list<string>  $used
     * @param  list<string>  $declared
     * @return list<string>
     */
    private static function pluralProblems(array $message, array $used, array $declared): array
    {
        if (! isset($message['plural'])) {
            return [];
        }

        $found = [];

        if (self::holesIn($message['plural']['one']) !== $used) {
            $found[] = "message [{$message['key']}] has a form for one with other placeholders than its text";
        }

        if (! in_array($message['plural']['by'], $declared, true)) {
            $found[] = "message [{$message['key']}] counts by [{$message['plural']['by']}], which it does not declare";
        }

        return $found;
    }

    /**
     * The names of the holes of a text, each once, in order of appearance: a hole is written `:name`.
     *
     * @return list<string>
     */
    private static function holesIn(string $text): array
    {
        preg_match_all('/:([a-z][a-z0-9]*)/', $text, $matches);

        return array_values(array_unique($matches[1]));
    }

    /**
     * @param  Spec  $spec
     * @return list<string>
     */
    private static function variableProblems(array $spec): array
    {
        $tokens = array_column($spec['tokens'], 'name', 'css');
        $owners = [];
        $found = [];

        foreach ($spec['components'] as $component) {
            foreach ($component['variables'] ?? [] as $variable) {
                $owners[$variable['name']][] = $component['name'];
            }
        }

        foreach ($owners as $name => $components) {
            if (count($components) > 1) {
                $found[] = "variable [{$name}] is declared twice";
            }

            foreach (isset($tokens[$name]) ? $components : [] as $component) {
                $found[] = "variable [{$name}] of component [{$component}] is the variable of token [{$tokens[$name]}]";
            }
        }

        return $found;
    }
}
