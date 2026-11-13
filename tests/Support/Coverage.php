<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

/**
 * The rule that the scenarios exercise what the components promise: every ARIA attribute and every
 * state attribute that a component declares is checked by an expectAttribute step in at least one
 * scenario of that same component. A check in the scenario of another component does not count.
 */
final class Coverage
{
    /**
     * @param  array{components: list<array<string, mixed>>, scenarios: list<array<string, mixed>>}  $spec
     * @return list<string>
     */
    public static function violations(array $spec): array
    {
        $checked = self::checked($spec['scenarios']);
        $found = [];

        foreach ($spec['components'] as $component) {
            $name = $component['name'];
            $declared = [
                ...array_column($component['accessibility']['aria'], 'attribute'),
                ...$component['attributes']['states'],
                ...References::stateOfParts($component),
            ];

            foreach ($declared as $attribute) {
                if (! in_array($attribute, $checked[$name] ?? [], true)) {
                    $found[] = "component [{$name}] declares [{$attribute}], which no scenario of the component checks";
                }
            }
        }

        return $found;
    }

    /**
     * The attributes that the steps of the scenarios check, by component.
     *
     * @param  list<array<string, mixed>>  $scenarios
     * @return array<string, list<string>>
     */
    private static function checked(array $scenarios): array
    {
        $checked = [];

        foreach ($scenarios as $scenario) {
            foreach ($scenario['steps'] as $step) {
                if ($step['action'] === 'expectAttribute') {
                    $checked[$scenario['component']][] = $step['attribute'];
                }
            }
        }

        return $checked;
    }
}
