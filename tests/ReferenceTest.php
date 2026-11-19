<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\References;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * @return array{components: list<array<string, mixed>>, tokens: list<array<string, mixed>>, scenarios: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>}
 */
function referenceTestSpec(): array
{
    return [
        'components' => Spec::data('components')['components'],
        'states' => Spec::data('components')['states'],
        'icons' => Spec::data('components')['icons'],
        'tokens' => Spec::data('tokens')['tokens'],
        'scenarios' => Spec::data('scenarios')['scenarios'],
    ];
}

it('finds no violation in the files of the specification', function () {
    expect(References::violations(referenceTestSpec()))->toBe([]);
});

it('names data files and schemas in the manifest that exist', function (string $key, string $kind) {
    expect(file_exists(Spec::root().'/'.Spec::pathOf($key, $kind)))->toBeTrue();
})->with([
    'the components file' => ['components', 'data'],
    'the components schema' => ['components', 'schema'],
    'the tokens file' => ['tokens', 'data'],
    'the tokens schema' => ['tokens', 'schema'],
    'the scenarios file' => ['scenarios', 'data'],
    'the scenarios schema' => ['scenarios', 'schema'],
]);

it('finds the mistake that was seeded', function (Closure $seed, string $fragment) {
    $violations = References::violations($seed(referenceTestSpec()));

    expect(implode("\n", $violations))->toContain($fragment);
})->with([
    'a component declared twice' => [
        fn (array $s): array => Spec::set($s, 'components.1', $s['components'][0]),
        'component [button] is declared twice',
    ],
    'a token declared twice' => [
        fn (array $s): array => Spec::set($s, 'tokens.'.count($s['tokens']), $s['tokens'][0]),
        'token [color-background] is declared twice',
    ],
    'a scenario declared twice' => [
        fn (array $s): array => Spec::set($s, 'scenarios.2', $s['scenarios'][0]),
        'scenario [button-loading-is-announced] is declared twice',
    ],
    'a prop declared twice' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.5', $s['components'][0]['props'][0]),
        'prop [variant] of component [button] is declared twice',
    ],
    'a slot declared twice' => [
        fn (array $s): array => Spec::set($s, 'components.0.slots.1', $s['components'][0]['slots'][0]),
        'slot [default] of component [button] is declared twice',
    ],
    'an attribute declared twice' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.2', $s['components'][0]['attributes']['states'][0]),
        'attribute [data-studeo-loading] of component [button] is declared twice',
    ],
    'a measure of a value that the prop does not have' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.1.measures', [['value' => 'xl', 'dimension' => 'height', 'length' => '3rem']]),
        'measure of value [xl] of prop [size] of component [button] is not a value of the prop',
    ],
    'a prop with measures that leaves one value without' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.1.measures', [['value' => 'sm', 'dimension' => 'height', 'length' => '1.75rem']]),
        'prop [size] of component [button] has no measure for value [md]',
    ],
    'a prop with two measures for one value' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.1.measures', [...$s['components'][0]['props'][1]['measures'], ['value' => 'sm', 'dimension' => 'height', 'length' => '2rem']]),
        'prop [size] of component [button] has two measures for value [sm]',
    ],
    'a component that composes one that does not exist' => [
        fn (array $s): array => Spec::set($s, 'components.0.composes', ['ghost']),
        'component [button] composes unknown component [ghost]',
    ],
    'a component that composes itself' => [
        fn (array $s): array => Spec::set($s, 'components.0.composes', ['button']),
        'component [button] composes itself',
    ],
    'a component that uses a token that does not exist' => [
        fn (array $s): array => Spec::set($s, 'components.0.tokens', ['color-missing']),
        'component [button] uses unknown token [color-missing]',
    ],
    'a scenario of a component that does not exist' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.component', 'ghost'),
        'scenario [button-loading-is-announced] names unknown component [ghost]',
    ],
    'a scenario with a fixture the component does not declare' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.fixture', 'button.ghost'),
        'scenario [button-loading-is-announced] has a fixture [button.ghost] that component [button] does not declare',
    ],
    'a fixture declared twice' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.4', $s['components'][0]['fixtures'][0]),
        'fixture [default] of component [button] is declared twice',
    ],
    'a fixture that sets a prop the component does not declare' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.props', ['ghost' => true]),
        'sets prop [ghost] that the component does not declare',
    ],
    'a fixture that sets an enum prop to a value outside its values' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.props', ['variant' => 'huge']),
        'sets prop [variant] to a value that does not fit its type',
    ],
    'a fixture that sets a list prop to text' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'components.0.props.4.type', 'list'), 'components.0.fixtures.0.props', ['loading' => 'a']),
        'fixture [default] of component [button] sets prop [loading] to a value that does not fit its type',
    ],
    'a fixture that sets a list prop to an object' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'components.0.props.4.type', 'list'), 'components.0.fixtures.0.props', ['loading' => ['a' => 'b']]),
        'fixture [default] of component [button] sets prop [loading] to a value that does not fit its type',
    ],
    'a fixture that sets a list prop to an option without a label' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'components.0.props.4.type', 'list'), 'components.0.fixtures.0.props', ['loading' => [['value' => 'a']]]),
        'fixture [default] of component [button] sets prop [loading] to a value that does not fit its type',
    ],
    'a fixture that sets a boolean prop to text' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.props', ['loading' => 'yes']),
        'sets prop [loading] to a value that does not fit its type',
    ],
    'a fixture that fills a slot the component does not declare' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.slots.ghost', 'x'),
        'fills slot [ghost] that the component does not declare',
    ],
    'a fixture that leaves a required slot empty' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.slots', []),
        'leaves the required slot [default] empty',
    ],
    'a fixture whose slots are an empty object' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.0.slots', new stdClass),
        'leaves the required slot [default] empty',
    ],
    'a fixture that puts an unknown fixture in a slot' => [
        fn (array $s): array => Spec::set($s, 'components.4.fixtures.0.slots.default', ['fixture' => 'input.ghost']),
        'puts the unknown fixture [input.ghost] in slot [default]',
    ],
    'a fixture that puts an unknown fixture in a list in a slot' => [
        fn (array $s): array => Spec::set($s, 'components.15.fixtures.1.slots.head', [['fixture' => 'table-column.default'], ['fixture' => 'table-column.ghost']]),
        'puts the unknown fixture [table-column.ghost] in slot [head]',
    ],
    'a fixture that is held by a fixture that does not exist' => [
        fn (array $s): array => Spec::set($s, 'components.16.fixtures.0.in.fixture', 'table.ghost'),
        'fixture [default] of component [table-column] is held by the unknown fixture [table.ghost]',
    ],
    'a fixture that is held in a slot that the host does not declare' => [
        fn (array $s): array => Spec::set($s, 'components.16.fixtures.0.in.slot', 'ghost'),
        'fixture [default] of component [table-column] is held in slot [ghost] that component [table] does not declare',
    ],
    'a fixture that is held in a slot that the host already fills' => [
        fn (array $s): array => Spec::set($s, 'components.16.fixtures.0.in', ['fixture' => 'table.default', 'slot' => 'caption']),
        'fixture [default] of component [table-column] is held in slot [caption] that fixture [table.default] already fills',
    ],
    'fixtures that hold each other' => [
        fn (array $s): array => Spec::set($s, 'components.15.fixtures.0.in', ['fixture' => 'table-row.bare', 'slot' => 'default']),
        'fixture [bare] of component [table] is held by itself through a chain of fixtures',
    ],
    'a component that sends attributes to a part and has no class prop for it' => [
        fn (array $s): array => Spec::forget($s, 'components.6.props.12'),
        'component [input] sends attributes to a part and does not declare the prop [control-class]',
    ],
    'a class prop for the control that is not of the type class' => [
        fn (array $s): array => Spec::set($s, 'components.6.props.12.type', 'string'),
        'prop [control-class] of component [input] must be of the type class',
    ],
    'a state that is not in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.2', 'data-studeo-ghost'),
        'state [data-studeo-ghost] of component [button] is not in the vocabulary of states',
    ],
    'a state of a part that is not in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts.0.states', ['data-studeo-ghost']),
        'state [data-studeo-ghost] of part [data-studeo-button-icon] of component [button] is not in the vocabulary of states',
    ],
    'a state of a part that looks like a part' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts.0.states', ['data-studeo-button-loading']),
        'state attribute [data-studeo-button-loading] of part [data-studeo-button-icon] of component [button] must not look like a part',
    ],
    'a state declared twice in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'states.'.count($s['states']), $s['states'][0]),
        'state [data-studeo-loading] is declared twice in the vocabulary',
    ],
    'an icon declared twice in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'icons.'.count($s['icons']), $s['icons'][0]),
        'icon [check] is declared twice in the vocabulary',
    ],
    'a default icon that is not in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.5.default', 'ghost'),
        'default icon [ghost] of prop [icon] of component [button] is not in the vocabulary of icons',
    ],
    'a fixture that uses an icon that is not in the vocabulary' => [
        fn (array $s): array => Spec::set($s, 'components.0.fixtures.3.props.icon', 'ghost'),
        'fixture [with-icon] of component [button] uses icon [ghost] that is not in the vocabulary of icons',
    ],
    'attributes sent to something that is not a part' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributesTarget', 'data-studeo-button-ghost'),
        'component [button] sends attributes to [data-studeo-button-ghost], which is neither root nor one of its parts',
    ],
    'a scenario with the fixture of another component' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.fixture', 'input.default'),
        'does not belong to component [button]',
    ],
    'a root attribute that does not follow the convention' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.root', 'data-studeo-btn'),
        'root attribute of component [button] must be [data-studeo-button]',
    ],
    'a part attribute that does not follow the convention' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts', [['name' => 'data-studeo-icon', 'description' => 'The icon.']]),
        'part attribute [data-studeo-icon] of component [button] must start with [data-studeo-button-]',
    ],
    'a state attribute that looks like a part' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.0', 'data-studeo-button-loading'),
        'state attribute [data-studeo-button-loading] of component [button] must not look like a part',
    ],
    'a variant prop that is not an enum' => [
        fn (array $s): array => Spec::forget(Spec::set($s, 'components.0.props.0.type', 'string'), 'components.0.props.0.values'),
        'prop [variant] of component [button] must be an enum with a default',
    ],
    'a size prop without a default' => [
        fn (array $s): array => Spec::forget($s, 'components.0.props.1.default'),
        'prop [size] of component [button] must be an enum with a default',
    ],
    'an enum default that is not one of the values' => [
        fn (array $s): array => Spec::set($s, 'components.0.props.2.default', 'link'),
        'default [link] of prop [type] of component [button] is not one of its values',
    ],
    'a scenario that targets an attribute the component does not declare' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.steps.0.target', ['attribute' => 'data-studeo-ghost']),
        'scenario [button-loading-is-announced] targets attribute [data-studeo-ghost]',
    ],
    'a scenario that checks an ARIA attribute the component does not list' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.steps.1.attribute', 'aria-expanded'),
        'checks attribute [aria-expanded] that component [button] does not declare',
    ],
    'a scenario that checks a data-studeo attribute the component does not declare' => [
        fn (array $s): array => Spec::set($s, 'scenarios.0.steps.2.attribute', 'data-studeo-open'),
        'checks attribute [data-studeo-open] that component [button] does not declare',
    ],
    'a scenario that checks disabled on a component with no such prop' => [
        fn (array $s): array => Spec::set(Spec::forget($s, 'components.0.props.3'), 'scenarios.1.steps.1', ['action' => 'expectAttribute', 'target' => ['role' => 'button', 'name' => 'Save'], 'attribute' => 'disabled']),
        'checks attribute [disabled] that component [button] does not declare',
    ],
]);
