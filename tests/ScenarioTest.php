<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Coverage;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * @return array{components: list<array<string, mixed>>, scenarios: list<array<string, mixed>>}
 */
function scenarioTestSpec(): array
{
    return [
        'components' => Spec::data('components')['components'],
        'scenarios' => Spec::data('scenarios')['scenarios'],
    ];
}

it('finds that every ARIA attribute and state a component declares is checked by a scenario', function () {
    expect(Coverage::violations(scenarioTestSpec()))->toBe([]);
});

it('finds the gap that was seeded', function (Closure $seed, string $fragment) {
    $violations = Coverage::violations($seed(scenarioTestSpec()));

    expect(implode("\n", $violations))->toContain($fragment);
})->with([
    'a check that was turned into a look' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.steps.1', ['action' => 'expectVisible', 'target' => ['role' => 'button', 'name' => 'Save']]),
        'component [button] declares [aria-busy], which no scenario of the component checks',
    ],
    'an ARIA attribute that was added to a component' => [
        fn (array $s): array => Spec::set($s, 'components.0.accessibility.aria.'.count($s['components'][0]['accessibility']['aria']), ['attribute' => 'aria-expanded', 'when' => 'Always.']),
        'component [button] declares [aria-expanded], which no scenario of the component checks',
    ],
    'a state that was added to a component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.'.count($s['components'][0]['attributes']['states']), 'data-studeo-open'),
        'component [button] declares [data-studeo-open], which no scenario of the component checks',
    ],
    'a state that was added to a part of a component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts.0.states', ['data-studeo-open']),
        'component [button] declares [data-studeo-open], which no scenario of the component checks',
    ],
    'a check that belongs to the scenario of another component' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.component', 'icon'),
        'component [button] declares [data-studeo-loading], which no scenario of the component checks',
    ],
    'a component that has no scenario at all' => [
        fn (array $s): array => Spec::set($s, 'scenarios', array_values(array_filter($s['scenarios'], fn (array $scenario): bool => $scenario['component'] !== 'link'))),
        'component [link] declares [aria-current], which no scenario of the component checks',
    ],
]);
