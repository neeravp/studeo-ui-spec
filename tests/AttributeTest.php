<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Attributes;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * @return array{components: list<array<string, mixed>>, documents: array<string, string>, vocabulary: list<string>}
 */
function attributeTestSpec(): array
{
    $documents = [];

    foreach (glob(Spec::root().'/docs/*.md') ?: [] as $path) {
        $documents['docs/'.basename($path)] = (string) file_get_contents($path);
    }

    foreach (['README.md', 'DESIGN.md', 'SPEC.md'] as $path) {
        $documents[$path] = (string) file_get_contents(Spec::root().'/'.$path);
    }

    $data = Spec::data('components');

    return ['components' => $data['components'], 'documents' => $documents, 'vocabulary' => array_column($data['states'], 'name')];
}

/**
 * A component with only what the rules read: a name and the attributes.
 *
 * @param  list<string>  $parts
 * @param  list<string>  $states
 * @return array<string, mixed>
 */
function attributeTestComponent(string $name, array $parts = [], array $states = []): array
{
    return [
        'name' => $name,
        'attributes' => [
            'root' => "data-studeo-{$name}",
            'parts' => array_map(fn (string $part): array => ['name' => $part, 'description' => 'A part.'], $parts),
            'states' => $states,
        ],
    ];
}

/**
 * @param  array{components: list<array<string, mixed>>, documents: array<string, string>, vocabulary: list<string>}  $spec
 * @return list<string>
 */
function attributeTestViolations(array $spec): array
{
    return Attributes::violations($spec['components'], $spec['documents'], $spec['vocabulary'], [Spec::data('tokens')['scope']['attribute']]);
}

it('finds no violation in the attributes of the components and in the documents', function () {
    $spec = attributeTestSpec();

    expect($spec['documents'])->toHaveKeys(['README.md', 'DESIGN.md', 'SPEC.md', 'docs/customization.md', 'docs/theming.md'])
        ->and(attributeTestViolations($spec))->toBe([]);
});

it('finds the mistake that was seeded', function (Closure $seed, string $fragment) {
    $violations = attributeTestViolations($seed(attributeTestSpec()));

    expect(implode("\n", $violations))->toContain($fragment);
})->with([
    'a part that is the root of another component' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'components.'.count($s['components']), attributeTestComponent('glyph', ['data-studeo-glyph-button'])), 'components.'.(count($s['components']) + 1), attributeTestComponent('glyph-button')),
        'attribute [data-studeo-glyph-button] is used as part of component [glyph] and as root of component [glyph-button]',
    ],
    'a state that is the root of its own component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.2', 'data-studeo-button'),
        'attribute [data-studeo-button] is used as root of component [button] and as state of component [button]',
    ],
    'a state that is the part of another component' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'components.'.count($s['components']), attributeTestComponent('widget', ['data-studeo-widget-control'])), 'components.0.attributes.states.2', 'data-studeo-widget-control'),
        'attribute [data-studeo-widget-control] is used as state of component [button] and as part of component [widget]',
    ],
    'a state that carries the name of another component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.states.2', 'data-studeo-input-extra'),
        'state attribute [data-studeo-input-extra] of component [button] carries the name of component [input]',
    ],
    'a state of a part that is the root of its own component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts.0.states', ['data-studeo-button']),
        'attribute [data-studeo-button] is used as root of component [button] and as state of component [button]',
    ],
    'a state of a part that carries the name of another component' => [
        fn (array $s): array => Spec::set($s, 'components.0.attributes.parts.0.states', ['data-studeo-input-extra']),
        'state attribute [data-studeo-input-extra] of component [button] carries the name of component [input]',
    ],
    'a state of the vocabulary that is the root of a component' => [
        fn (array $s): array => Spec::set($s, 'vocabulary.'.count($s['vocabulary']), 'data-studeo-button'),
        'attribute [data-studeo-button] is used as root of component [button] and as state of the vocabulary',
    ],
    'a state of the vocabulary that carries the name of a component' => [
        fn (array $s): array => Spec::set($s, 'vocabulary.'.count($s['vocabulary']), 'data-studeo-button-extra'),
        'state attribute [data-studeo-button-extra] of the vocabulary carries the name of component [button]',
    ],
    'a document that names an attribute no component declares' => [
        fn (array $s): array => Spec::set($s, 'documents.extra', 'Select `data-studeo-ghost` to style it.'),
        'document [extra] names attribute [data-studeo-ghost] that no component declares',
    ],
]);

it('lets two components share the name of a state', function () {
    $spec = attributeTestSpec();
    $spec = Spec::set($spec, 'components.'.count($spec['components']), attributeTestComponent('textarea', [], ['data-studeo-loading']));

    expect(attributeTestViolations($spec))->toBe([]);
});

it('lets a part carry a state that its component does not carry', function () {
    $spec = attributeTestSpec();
    $spec = Spec::set($spec, 'components.'.count($spec['components']), attributeTestComponent('widget', ['data-studeo-widget-control']));
    $spec = Spec::set($spec, 'components.'.(count($spec['components']) - 1).'.attributes.parts.0.states', ['data-studeo-disabled']);

    expect(attributeTestViolations($spec))->toBe([]);
});

it('does not take a placeholder or a name that is declared for a mistake', function () {
    $text = 'Use data-studeo-<component> and data-studeo-<component>-<part> and data-studeo-* and data-studeo-button and data-studeo-loading.';
    $spec = Spec::set(attributeTestSpec(), 'documents.extra', $text);

    expect(attributeTestViolations($spec))->toBe([]);
});
