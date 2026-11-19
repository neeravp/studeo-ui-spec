<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rules that a schema cannot state because they look across files or inside a list: names are
 * unique, every reference resolves, attribute names follow the convention, the fixtures of a
 * component fit its props and slots, and a scenario uses only what its component declares.
 *
 * @phpstan-type Spec array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}
 */
final class References
{
    /**
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    public static function violations(array $spec): array
    {
        return [
            ...self::repeatedNames($spec),
            ...self::unresolvedReferences($spec),
            ...self::attributeNaming($spec['components']),
            ...self::vocabularyProblems($spec),
            ...self::targetProblems($spec['components']),
            ...self::propConventions($spec['components']),
            ...self::fixtureProblems($spec),
            ...self::scenarioAttributes($spec),
        ];
    }

    /**
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function repeatedNames(array $spec): array
    {
        $found = [];

        foreach (self::repeated(array_column($spec['components'], 'name')) as $name) {
            $found[] = "component [{$name}] is declared twice";
        }

        foreach (self::repeated(array_column($spec['tokens'], 'name')) as $name) {
            $found[] = "token [{$name}] is declared twice";
        }

        foreach (self::repeated(array_column($spec['scenarios'], 'id')) as $name) {
            $found[] = "scenario [{$name}] is declared twice";
        }

        foreach (self::repeated(array_column($spec['states'], 'name')) as $name) {
            $found[] = "state [{$name}] is declared twice in the vocabulary";
        }

        foreach (self::repeated(array_column($spec['icons'], 'name')) as $name) {
            $found[] = "icon [{$name}] is declared twice in the vocabulary";
        }

        return [...$found, ...self::repeatedInsideComponents($spec['components'])];
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function repeatedInsideComponents(array $components): array
    {
        $found = [];

        foreach ($components as $component) {
            foreach (self::repeated(array_column($component['props'], 'name')) as $name) {
                $found[] = "prop [{$name}] of component [{$component['name']}] is declared twice";
            }

            foreach (self::repeated(array_column($component['slots'], 'name')) as $name) {
                $found[] = "slot [{$name}] of component [{$component['name']}] is declared twice";
            }

            foreach (self::repeated(self::ownAttributeNames($component)) as $name) {
                $found[] = "attribute [{$name}] of component [{$component['name']}] is declared twice";
            }

            foreach (self::repeated(array_column($component['fixtures'], 'name')) as $name) {
                $found[] = "fixture [{$name}] of component [{$component['name']}] is declared twice";
            }
        }

        return $found;
    }

    /**
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function unresolvedReferences(array $spec): array
    {
        $components = array_column($spec['components'], 'name');
        $tokens = array_column($spec['tokens'], 'name');
        $found = [];

        foreach ($spec['components'] as $component) {
            $found = [...$found, ...self::unresolvedComposes($component, $components), ...self::unresolvedTokens($component, $tokens)];
        }

        foreach ($spec['scenarios'] as $scenario) {
            $found = [...$found, ...self::unresolvedScenario($scenario, $spec['components'])];
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $component
     * @param  list<string>  $components
     * @return list<string>
     */
    private static function unresolvedComposes(array $component, array $components): array
    {
        $found = [];

        foreach ($component['composes'] as $name) {
            if ($name === $component['name']) {
                $found[] = "component [{$name}] composes itself";
            } elseif (! in_array($name, $components, true)) {
                $found[] = "component [{$component['name']}] composes unknown component [{$name}]";
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $component
     * @param  list<string>  $tokens
     * @return list<string>
     */
    private static function unresolvedTokens(array $component, array $tokens): array
    {
        $unknown = array_diff($component['tokens'], $tokens);

        return array_values(array_map(fn (string $name): string => "component [{$component['name']}] uses unknown token [{$name}]", $unknown));
    }

    /**
     * @param  array<string, mixed>  $scenario
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function unresolvedScenario(array $scenario, array $components): array
    {
        $byName = array_column($components, null, 'name');
        $component = $byName[$scenario['component']] ?? null;

        if ($component === null) {
            return ["scenario [{$scenario['id']}] names unknown component [{$scenario['component']}]"];
        }

        if (! str_starts_with($scenario['fixture'], $scenario['component'].'.')) {
            return ["scenario [{$scenario['id']}] has a fixture [{$scenario['fixture']}] that does not belong to component [{$scenario['component']}]"];
        }

        if (! in_array($scenario['fixture'], self::fixtureIds($component), true)) {
            return ["scenario [{$scenario['id']}] has a fixture [{$scenario['fixture']}] that component [{$scenario['component']}] does not declare"];
        }

        return [];
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function attributeNaming(array $components): array
    {
        $found = [];

        foreach ($components as $component) {
            $name = $component['name'];
            $root = $component['attributes']['root'];

            if ($root !== "data-studeo-{$name}") {
                $found[] = "root attribute of component [{$name}] must be [data-studeo-{$name}], not [{$root}]";
            }

            foreach ($component['attributes']['parts'] as $part) {
                if (! str_starts_with($part['name'], "data-studeo-{$name}-")) {
                    $found[] = "part attribute [{$part['name']}] of component [{$name}] must start with [data-studeo-{$name}-]";
                }
            }

            foreach ($component['attributes']['states'] as $state) {
                if (str_starts_with($state, "data-studeo-{$name}-")) {
                    $found[] = "state attribute [{$state}] of component [{$name}] must not look like a part";
                }
            }

            foreach ($component['attributes']['parts'] as $part) {
                foreach ($part['states'] ?? [] as $state) {
                    if (str_starts_with($state, "data-studeo-{$name}-")) {
                        $found[] = "state attribute [{$state}] of part [{$part['name']}] of component [{$name}] must not look like a part";
                    }
                }
            }
        }

        return $found;
    }

    /**
     * The states of a component come from the vocabulary; the icons that props and fixtures name are
     * in the vocabulary of icons.
     *
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function vocabularyProblems(array $spec): array
    {
        $states = array_column($spec['states'], 'name');
        $icons = array_column($spec['icons'], 'name');
        $found = [];

        foreach ($spec['components'] as $component) {
            foreach (array_diff($component['attributes']['states'], $states) as $state) {
                $found[] = "state [{$state}] of component [{$component['name']}] is not in the vocabulary of states";
            }

            foreach ($component['attributes']['parts'] as $part) {
                foreach (array_diff($part['states'] ?? [], $states) as $state) {
                    $found[] = "state [{$state}] of part [{$part['name']}] of component [{$component['name']}] is not in the vocabulary of states";
                }
            }

            $found = [...$found, ...self::iconProblems($component, $icons)];
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $component
     * @param  list<string>  $icons
     * @return list<string>
     */
    private static function iconProblems(array $component, array $icons): array
    {
        $found = [];
        $iconProps = array_column(array_filter($component['props'], fn (array $prop): bool => $prop['type'] === 'icon'), null, 'name');

        foreach ($iconProps as $prop) {
            if (isset($prop['default']) && ! in_array($prop['default'], $icons, true)) {
                $found[] = "default icon [{$prop['default']}] of prop [{$prop['name']}] of component [{$component['name']}] is not in the vocabulary of icons";
            }
        }

        foreach ($component['fixtures'] as $fixture) {
            foreach ($fixture['props'] as $name => $value) {
                if (isset($iconProps[$name]) && ! in_array($value, $icons, true)) {
                    $found[] = "fixture [{$fixture['name']}] of component [{$component['name']}] uses icon [{$value}] that is not in the vocabulary of icons";
                }
            }
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function targetProblems(array $components): array
    {
        $found = [];

        foreach ($components as $component) {
            $target = $component['attributesTarget'];
            $parts = array_column($component['attributes']['parts'], 'name');

            if ($target !== 'root' && ! in_array($target, $parts, true)) {
                $found[] = "component [{$component['name']}] sends attributes to [{$target}], which is neither root nor one of its parts";
            }

            if ($target !== 'root') {
                $classProp = array_column($component['props'], null, 'name')['control-class'] ?? null;

                if ($classProp === null) {
                    $found[] = "component [{$component['name']}] sends attributes to a part and does not declare the prop [control-class]";
                } elseif ($classProp['type'] !== 'class') {
                    $found[] = "prop [control-class] of component [{$component['name']}] must be of the type class";
                }
            }
        }

        return $found;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return list<string>
     */
    private static function propConventions(array $components): array
    {
        $found = [];

        foreach ($components as $component) {
            foreach ($component['props'] as $prop) {
                $found = [...$found, ...self::propProblems($component['name'], $prop)];
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $prop
     * @return list<string>
     */
    private static function propProblems(string $component, array $prop): array
    {
        $isAxis = in_array($prop['name'], ['variant', 'size'], true);

        if ($isAxis && ($prop['type'] !== 'enum' || ! array_key_exists('default', $prop))) {
            return ["prop [{$prop['name']}] of component [{$component}] must be an enum with a default"];
        }

        if ($prop['type'] === 'enum' && array_key_exists('default', $prop) && ! in_array($prop['default'], $prop['values'], true)) {
            return ["default [{$prop['default']}] of prop [{$prop['name']}] of component [{$component}] is not one of its values"];
        }

        return self::measureProblems($component, $prop);
    }

    /**
     * @param  array<string, mixed>  $prop
     * @return list<string>
     */
    private static function measureProblems(string $component, array $prop): array
    {
        $measured = array_column($prop['measures'] ?? [], 'value');

        if ($measured === []) {
            return [];
        }

        $found = [];

        foreach (array_diff($measured, $prop['values']) as $value) {
            $found[] = "measure of value [{$value}] of prop [{$prop['name']}] of component [{$component}] is not a value of the prop";
        }

        foreach (array_diff($prop['values'], $measured) as $value) {
            $found[] = "prop [{$prop['name']}] of component [{$component}] has no measure for value [{$value}]";
        }

        foreach (self::repeated($measured) as $value) {
            $found[] = "prop [{$prop['name']}] of component [{$component}] has two measures for value [{$value}]";
        }

        return $found;
    }

    /**
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function fixtureProblems(array $spec): array
    {
        $ids = [];

        foreach ($spec['components'] as $component) {
            $ids = [...$ids, ...self::fixtureIds($component)];
        }

        $found = [];

        foreach ($spec['components'] as $component) {
            foreach ($component['fixtures'] as $fixture) {
                $found = [
                    ...$found,
                    ...self::fixturePropProblems($component, $fixture),
                    ...self::fixtureSlotProblems($component, $fixture, $ids),
                ];
            }
        }

        return [...$found, ...self::hostProblems($spec)];
    }

    /**
     * @param  array<string, mixed>  $component
     * @param  array<string, mixed>  $fixture
     * @return list<string>
     */
    private static function fixturePropProblems(array $component, array $fixture): array
    {
        $byName = array_column($component['props'], null, 'name');
        $found = [];

        foreach ($fixture['props'] as $name => $value) {
            $label = "fixture [{$fixture['name']}] of component [{$component['name']}]";

            if (! isset($byName[$name])) {
                $found[] = "{$label} sets prop [{$name}] that the component does not declare";
            } elseif (! self::fits($byName[$name], $value)) {
                $found[] = "{$label} sets prop [{$name}] to a value that does not fit its type";
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $prop
     */
    private static function fits(array $prop, mixed $value): bool
    {
        return match ($prop['type']) {
            'boolean' => is_bool($value),
            'integer' => is_int($value),
            'enum' => in_array($value, $prop['values'], true),
            'list' => is_array($value) && array_is_list($value) && array_reduce($value, fn (bool $fits, mixed $item): bool => $fits && (is_string($item) || is_int($item) || (is_array($item) && array_keys($item) === ['value', 'label'])), true),
            default => is_string($value),
        };
    }

    /**
     * The fixtures that a slot holds: none for text, one for a fixture, several for a list.
     *
     * @return list<string>
     */
    private static function heldFixtures(mixed $content): array
    {
        if (! is_array($content)) {
            return [];
        }

        return array_key_exists('fixture', $content) ? [$content['fixture']] : array_column($content, 'fixture');
    }

    /**
     * A fixture of a component that cannot stand alone names the fixture that holds it: that fixture
     * must exist, its component must declare the slot, the slot must be free, and the fixtures must
     * not hold each other.
     *
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function hostProblems(array $spec): array
    {
        $byId = [];

        foreach ($spec['components'] as $component) {
            foreach ($component['fixtures'] as $fixture) {
                $byId["{$component['name']}.{$fixture['name']}"] = [$component, $fixture];
            }
        }

        $found = [];

        foreach ($byId as [$component, $fixture]) {
            if (! isset($fixture['in'])) {
                continue;
            }

            $label = "fixture [{$fixture['name']}] of component [{$component['name']}]";
            $host = $fixture['in']['fixture'];
            $slot = $fixture['in']['slot'];

            if (! isset($byId[$host])) {
                $found[] = "{$label} is held by the unknown fixture [{$host}]";

                continue;
            }

            [$hostComponent, $hostFixture] = $byId[$host];

            if (! in_array($slot, array_column($hostComponent['slots'], 'name'), true)) {
                $found[] = "{$label} is held in slot [{$slot}] that component [{$hostComponent['name']}] does not declare";
            } elseif (array_key_exists($slot, (array) $hostFixture['slots'])) {
                $found[] = "{$label} is held in slot [{$slot}] that fixture [{$host}] already fills";
            }

            $at = $host;

            for ($step = 0; $step <= count($byId); $step++) {
                if ($at === "{$component['name']}.{$fixture['name']}") {
                    $found[] = "{$label} is held by itself through a chain of fixtures";

                    break;
                }

                $at = $byId[$at][1]['in']['fixture'] ?? null;

                if ($at === null || ! isset($byId[$at])) {
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $component
     * @param  array<string, mixed>  $fixture
     * @param  list<string>  $ids
     * @return list<string>
     */
    private static function fixtureSlotProblems(array $component, array $fixture, array $ids): array
    {
        $label = "fixture [{$fixture['name']}] of component [{$component['name']}]";
        $declared = array_column($component['slots'], null, 'name');
        $slots = (array) $fixture['slots'];
        $found = [];

        foreach ($slots as $name => $content) {
            if (! isset($declared[$name])) {
                $found[] = "{$label} fills slot [{$name}] that the component does not declare";
            }

            foreach (self::heldFixtures($content) as $held) {
                if (! in_array($held, $ids, true)) {
                    $found[] = "{$label} puts the unknown fixture [{$held}] in slot [{$name}]";
                }
            }
        }

        foreach ($declared as $name => $slot) {
            if ($slot['required'] && ! array_key_exists($name, $slots)) {
                $found[] = "{$label} leaves the required slot [{$name}] empty";
            }
        }

        return $found;
    }

    /**
     * @param  array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    private static function scenarioAttributes(array $spec): array
    {
        $byName = array_column($spec['components'], null, 'name');
        $found = [];

        foreach ($spec['scenarios'] as $scenario) {
            $component = $byName[$scenario['component']] ?? null;

            if ($component !== null) {
                $found = [...$found, ...self::stepProblems($scenario, $component)];
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $scenario
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    private static function stepProblems(array $scenario, array $component): array
    {
        $found = [];

        foreach ($scenario['steps'] as $step) {
            $target = $step['target']['attribute'] ?? null;

            if ($target !== null && ! in_array($target, self::attributeNames($component), true)) {
                $found[] = "scenario [{$scenario['id']}] targets attribute [{$target}] that component [{$component['name']}] does not declare";
            }

            $checked = $step['attribute'] ?? null;

            if ($checked !== null && ! self::declares($component, $checked)) {
                $found[] = "scenario [{$scenario['id']}] checks attribute [{$checked}] that component [{$component['name']}] does not declare";
            }
        }

        return $found;
    }

    /**
     * Whether a component declares the attribute that a scenario checks: an ARIA attribute it lists, a
     * data-studeo-* attribute it names, or `disabled` when it has a prop of that name.
     *
     * @param  array<string, mixed>  $component
     */
    private static function declares(array $component, string $attribute): bool
    {
        if (str_starts_with($attribute, 'aria-')) {
            return in_array($attribute, array_column($component['accessibility']['aria'], 'attribute'), true);
        }

        if ($attribute === 'disabled') {
            return in_array('disabled', array_column($component['props'], 'name'), true);
        }

        return in_array($attribute, self::attributeNames($component), true);
    }

    /**
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    private static function attributeNames(array $component): array
    {
        return [...self::ownAttributeNames($component), ...self::stateOfParts($component)];
    }

    /**
     * The root, the parts and the states of the component itself, each as the data declares it.
     *
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    private static function ownAttributeNames(array $component): array
    {
        return [
            $component['attributes']['root'],
            ...array_column($component['attributes']['parts'], 'name'),
            ...$component['attributes']['states'],
        ];
    }

    /**
     * The states that the parts of a component carry.
     *
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    public static function stateOfParts(array $component): array
    {
        $states = [];

        foreach ($component['attributes']['parts'] as $part) {
            array_push($states, ...($part['states'] ?? []));
        }

        return $states;
    }

    /**
     * @param  array<string, mixed>  $component
     * @return list<string>
     */
    private static function fixtureIds(array $component): array
    {
        return array_map(fn (string $name): string => "{$component['name']}.{$name}", array_column($component['fixtures'], 'name'));
    }

    /**
     * @param  array<array-key, mixed>  $values
     * @return list<string>
     */
    private static function repeated(array $values): array
    {
        $counts = array_count_values(array_map('strval', $values));

        return array_values(array_map('strval', array_keys(array_filter($counts, fn (int $count): bool => $count > 1))));
    }
}
