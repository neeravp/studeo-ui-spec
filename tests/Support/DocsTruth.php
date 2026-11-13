<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

use Closure;

/**
 * The rules that keep the documents true to the data files: every component has its heading in the
 * reference, in the order of the data; every component, state, icon, action, token and pair of colours
 * has its row in the document that lists it; every action has its meaning in the specification; no
 * document keeps an unfinished placeholder; every link
 * between documents leads to a file, and to a heading when it names one. The rules read the data and
 * the text of the documents and nothing else, so they hold whatever tool wrote the regions.
 */
final class DocsTruth
{
    private const string REFERENCE = 'docs/components.md';

    private const string SPEC = 'SPEC.md';

    private const string THEMING = 'docs/theming.md';

    /**
     * @param  array{components: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>, tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>, actions: list<string>}  $spec
     * @param  array<string, string>  $documents  the text of each document, by its path
     * @param  Closure(string): bool  $exists  whether a file of the project exists, by its path
     * @return list<string>
     */
    public static function violations(array $spec, array $documents, Closure $exists): array
    {
        return [
            ...self::headings($spec['components'], $documents[self::REFERENCE] ?? ''),
            ...self::components($spec['components'], $documents[self::SPEC] ?? ''),
            ...self::rows(self::SPEC, $documents[self::SPEC] ?? '', 'state', array_column($spec['states'], 'name'), fn (string $name): string => "| `{$name}` |"),
            ...self::rows(self::SPEC, $documents[self::SPEC] ?? '', 'icon', array_column($spec['icons'], 'name'), fn (string $name): string => "| `{$name}` |"),
            ...self::rows(self::SPEC, $documents[self::SPEC] ?? '', 'action', $spec['actions'], fn (string $name): string => "- `{$name}` ("),
            ...self::meanings($spec['actions'], $documents[self::SPEC] ?? ''),
            ...self::rows(self::THEMING, $documents[self::THEMING] ?? '', 'token', array_column($spec['tokens'], 'name'), function (string $name) use ($spec): string {
                $css = array_column($spec['tokens'], 'css', 'name')[$name];

                return "| `{$name}` | `{$css}` |";
            }),
            ...self::contrast($spec['contrast'], $documents[self::THEMING] ?? ''),
            ...self::placeholders($documents),
            ...self::links($documents, $exists),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function headings(array $components, string $reference): array
    {
        $names = array_column($components, 'name');
        $found = self::headingsOf($reference);
        $problems = [];

        foreach (array_diff($names, $found) as $name) {
            $problems[] = self::REFERENCE." has no heading for component [{$name}]";
        }

        foreach (array_diff($found, $names) as $name) {
            $problems[] = self::REFERENCE." has a heading [{$name}] for a component that does not exist";
        }

        if ($problems === [] && $found !== $names) {
            $problems[] = self::REFERENCE.' lists the components in a different order from components.json';
        }

        return $problems;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function components(array $components, string $spec): array
    {
        $names = array_column($components, 'name');

        return self::rows(self::SPEC, $spec, 'component', $names, fn (string $name): string => "| [`{$name}`](docs/components.md#{$name}) |");
    }

    /**
     * @param  list<string>  $names
     * @param  Closure(string): string  $prefix  the start of the line that lists a name
     * @return list<string>
     */
    private static function rows(string $path, string $text, string $kind, array $names, Closure $prefix): array
    {
        $lines = explode("\n", $text);
        $problems = [];

        foreach ($names as $name) {
            $start = $prefix($name);

            if (array_filter($lines, fn (string $line): bool => str_starts_with($line, $start)) === []) {
                $problems[] = "{$path} has no row for {$kind} [{$name}]";
            }
        }

        return $problems;
    }

    /**
     * The specification has a section on finding the target of a step and a section on what a step means,
     * and the second one has a line for every action of the closed list.
     *
     * @param  list<string>  $actions
     * @return list<string>
     */
    private static function meanings(array $actions, string $spec): array
    {
        $text = (string) preg_replace('/```.*?```/s', '', $spec);
        $problems = [];

        foreach (['Finding the target of a step', 'What a step means'] as $title) {
            if (preg_match('/^### '.preg_quote($title, '/').'$/m', $text) !== 1) {
                $problems[] = self::SPEC." has no section [{$title}]";
            }
        }

        if (preg_match('/^### What a step means$(.*?)(?=^#{1,3} |\z)/ms', $text, $section) !== 1) {
            return $problems;
        }

        $lines = explode("\n", $section[1]);

        foreach ($actions as $action) {
            if (array_filter($lines, fn (string $line): bool => str_starts_with($line, "- `{$action}`: ")) === []) {
                $problems[] = self::SPEC." has no meaning for action [{$action}]";
            }
        }

        return $problems;
    }

    /**
     * @param  list<array<string, mixed>>  $pairs
     * @return list<string>
     */
    private static function contrast(array $pairs, string $theming): array
    {
        $lines = explode("\n", $theming);
        $problems = [];

        foreach ($pairs as $pair) {
            $start = "| `{$pair['foreground']}` | `{$pair['background']}` | {$pair['minimum']} |";

            if (array_filter($lines, fn (string $line): bool => str_starts_with($line, $start)) === []) {
                $problems[] = self::THEMING." has no row for the pair [{$pair['foreground']} on {$pair['background']}]";
            }
        }

        return $problems;
    }

    /**
     * @param  array<string, string>  $documents
     * @return list<string>
     */
    private static function placeholders(array $documents): array
    {
        $problems = [];

        foreach ($documents as $path => $text) {
            foreach (explode("\n", $text) as $number => $line) {
                if (str_starts_with(trim($line), '<!-- TODO(docs):')) {
                    $problems[] = "{$path}:".($number + 1).' has an unfinished placeholder';
                }
            }
        }

        return $problems;
    }

    /**
     * @param  array<string, string>  $documents
     * @param  Closure(string): bool  $exists
     * @return list<string>
     */
    private static function links(array $documents, Closure $exists): array
    {
        $problems = [];

        foreach ($documents as $path => $text) {
            preg_match_all('/\]\(([^)\s]+)\)/', (string) preg_replace('/```.*?```/s', '', $text), $matches);

            foreach ($matches[1] as $link) {
                if (preg_match('#^([a-z][a-z0-9+.-]*:|\#)#i', $link) === 1) {
                    continue;
                }

                [$target, $fragment] = array_pad(explode('#', $link, 2), 2, null);
                $file = self::resolve(dirname($path), $target);

                if (! $exists($file)) {
                    $problems[] = "{$path} links to [{$file}], which does not exist";

                    continue;
                }

                if ($fragment !== null && isset($documents[$file]) && ! in_array($fragment, self::anchorsOf($documents[$file]), true)) {
                    $problems[] = "{$path} links to [{$file}#{$fragment}], which has no such heading";
                }
            }
        }

        return $problems;
    }

    /**
     * The path that a link written in a document of a folder leads to, from the root of the project.
     */
    private static function resolve(string $folder, string $target): string
    {
        $parts = $folder === '.' ? [] : explode('/', $folder);

        foreach (explode('/', $target) as $segment) {
            if ($segment === '..') {
                array_pop($parts);
            } elseif ($segment !== '.' && $segment !== '') {
                $parts[] = $segment;
            }
        }

        return implode('/', $parts);
    }

    /**
     * The names of the second-level headings of a document, outside code blocks.
     *
     * @return list<string>
     */
    private static function headingsOf(string $text): array
    {
        preg_match_all('/^## (.+)$/m', (string) preg_replace('/```.*?```/s', '', $text), $matches);

        return array_values($matches[1]);
    }

    /**
     * The anchors of every heading of a document, written the way the places that show documents write them.
     *
     * @return list<string>
     */
    private static function anchorsOf(string $text): array
    {
        preg_match_all('/^#{1,6} (.+)$/m', (string) preg_replace('/```.*?```/s', '', $text), $matches);

        return array_map(
            fn (string $heading): string => str_replace(' ', '-', (string) preg_replace('/[^a-z0-9 -]/', '', strtolower($heading))),
            $matches[1],
        );
    }
}
