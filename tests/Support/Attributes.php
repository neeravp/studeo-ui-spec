<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rules about the data-studeo-* attributes that look across components and documents: a root or
 * a part is the attribute of one component only and is no state; a state carries no component
 * name; a document names only attributes that a component or the vocabulary of states declares.
 */
final class Attributes
{
    /**
     * @param  list<array<string, mixed>>  $components
     * @param  array<string, string>  $documents  the text of each document, by its path
     * @param  list<string>  $vocabulary  the names of the states of the vocabulary
     * @return list<string>
     */
    public static function violations(array $components, array $documents, array $vocabulary = []): array
    {
        return [
            ...self::collisions($components, $vocabulary),
            ...self::statesNamedAfterComponents($components, $vocabulary),
            ...self::unknownMentions($components, $documents, $vocabulary),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @param  list<string>  $vocabulary
     * @return list<string>
     */
    private static function collisions(array $components, array $vocabulary): array
    {
        $found = [];

        foreach (self::uses($components, $vocabulary) as $name => $uses) {
            $owned = array_filter($uses, fn (string $use): bool => ! str_starts_with($use, 'state'));

            if ($owned !== [] && count($uses) > 1) {
                $found[] = "attribute [{$name}] is used as ".implode(' and as ', $uses);
            }
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @param  list<string>  $vocabulary
     * @return array<string, list<string>>
     */
    private static function uses(array $components, array $vocabulary): array
    {
        $uses = [];

        foreach ($components as $component) {
            $uses[$component['attributes']['root']][] = "root of component [{$component['name']}]";

            foreach ($component['attributes']['parts'] as $part) {
                $uses[$part['name']][] = "part of component [{$component['name']}]";
            }

            foreach ([...$component['attributes']['states'], ...References::stateOfParts($component)] as $state) {
                $uses[$state][] = "state of component [{$component['name']}]";
            }
        }

        foreach ($vocabulary as $state) {
            $uses[$state][] = 'state of the vocabulary';
        }

        return $uses;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @param  list<string>  $vocabulary
     * @return list<string>
     */
    private static function statesNamedAfterComponents(array $components, array $vocabulary): array
    {
        $found = [];

        foreach ($components as $component) {
            foreach ([...$component['attributes']['states'], ...References::stateOfParts($component)] as $state) {
                $found = [...$found, ...self::componentsNamedIn($state, "component [{$component['name']}]", $components)];
            }
        }

        foreach ($vocabulary as $state) {
            $found = [...$found, ...self::componentsNamedIn($state, 'the vocabulary', $components)];
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function componentsNamedIn(string $state, string $owner, array $components): array
    {
        $found = [];

        foreach ($components as $other) {
            if (str_starts_with($state, "data-studeo-{$other['name']}-")) {
                $found[] = "state attribute [{$state}] of {$owner} carries the name of component [{$other['name']}]";
            }
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @param  array<string, string>  $documents
     * @param  list<string>  $vocabulary
     * @return list<string>
     */
    private static function unknownMentions(array $components, array $documents, array $vocabulary): array
    {
        $declared = array_keys(self::uses($components, $vocabulary));
        $found = [];

        foreach ($documents as $path => $text) {
            preg_match_all('/data-studeo-[a-z0-9]+(?:-[a-z0-9]+)*/', $text, $matches);

            foreach (array_unique($matches[0]) as $name) {
                if (! in_array($name, $declared, true)) {
                    $found[] = "document [{$path}] names attribute [{$name}] that no component declares";
                }
            }
        }

        return $found;
    }
}
