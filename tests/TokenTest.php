<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Spec;
use Studeo\UiSpec\Tests\Support\Tokens;

/**
 * @return array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}
 */
function tokenTestSpec(): array
{
    $data = Spec::data('tokens');

    return ['tokens' => $data['tokens'], 'knobs' => $data['knobs'], 'contrast' => $data['contrast']];
}

/**
 * @param  array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
 */
function tokenTestIndex(array $spec, string $name): int
{
    return (int) array_search($name, array_column($spec['tokens'], 'name'), true);
}

/**
 * @param  array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
 */
function tokenTestPairIndex(array $spec, string $foreground, string $background): int
{
    foreach ($spec['contrast'] as $index => $pair) {
        if ($pair['foreground'] === $foreground && $pair['background'] === $background) {
            return $index;
        }
    }

    throw new RuntimeException("No pair of [{$foreground}] on [{$background}].");
}

/**
 * @param  array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
 * @return array{tokens: list<array<string, mixed>>, knobs: list<array<string, mixed>>, contrast: list<array<string, mixed>>}
 */
function tokenTestRule(array $spec, string $name, string $rule): array
{
    return Spec::set($spec, 'tokens.'.tokenTestIndex($spec, $name).'.derive', $rule);
}

it('finds no violation in the tokens of the specification', function () {
    expect(Tokens::violations(tokenTestSpec()))->toBe([]);
});

it('finds the mistake that was seeded', function (Closure $seed, string $fragment) {
    $violations = Tokens::violations($seed(tokenTestSpec()));

    expect(implode("\n", $violations))->toContain($fragment);
})->with([
    'a variable that does not follow the name' => [
        fn (array $s): array => Spec::set($s, 'tokens.0.css', '--studeo-other'),
        'token [color-background] must have the variable [--studeo-color-background], not [--studeo-other]',
    ],
    'a name that does not start with its category' => [
        fn (array $s): array => Spec::set($s, 'tokens.0.category', 'space'),
        'token [color-background] of category [space] must start with [space-]',
    ],
    'a layout value written as a token' => [
        fn (array $s): array => Spec::set(Spec::set($s, 'tokens.0.name', 'space-4'), 'tokens.0.category', 'space'),
        'token [space-4] is not a colour, a radius or a font family: a layout value is a utility class, never a token',
    ],
    'a font size written as a token' => [
        fn (array $s): array => Spec::set($s, 'tokens.0.name', 'font-size-sm'),
        'token [font-size-sm] is not a colour, a radius or a font family',
    ],
    'a pair that names an unknown foreground' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.foreground', 'color-missing'),
        'names unknown token [color-missing]',
    ],
    'a pair that names an unknown background' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'color-missing'),
        'names unknown token [color-missing]',
    ],
    'a pair that uses a token that is not a colour' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'radius-control'),
        'uses token [radius-control] that is not a colour',
    ],
    'a pair that uses a constant' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'color-overlay'),
        'uses token [color-overlay] that is a constant and takes part in no contrast',
    ],
    'an input that became a derived colour' => [
        fn (array $s): array => Spec::set($s, 'tokens.'.tokenTestIndex($s, 'color-text').'.kind', 'derived'),
        'the inputs must be [color-background, color-text, color-accent',
    ],
    'a derived colour that became an input' => [
        fn (array $s): array => Spec::set($s, 'tokens.'.tokenTestIndex($s, 'color-surface').'.kind', 'input'),
        'the inputs must be [color-background, color-text, color-accent',
    ],
    'a rule that reads a variable that is no token' => [
        fn (array $s): array => tokenTestRule($s, 'color-surface', 'color-mix(in oklab, var(--studeo-color-nothing), white 8%)'),
        'the rule of token [color-surface] reads [--studeo-color-nothing], which is neither a token nor a knob',
    ],
    'a rule that holds a hex colour' => [
        fn (array $s): array => tokenTestRule($s, 'color-surface', 'color-mix(in oklab, #ffffff, var(--studeo-color-text) 8%)'),
        'the rule of token [color-surface] holds a literal colour',
    ],
    'a rule that holds a colour function with numbers' => [
        fn (array $s): array => tokenTestRule($s, 'color-field', 'oklch(0.9 0.01 250)'),
        'the rule of token [color-field] holds a literal colour',
    ],
    'a rule that reads itself through another rule' => [
        fn (array $s): array => tokenTestRule($s, 'color-surface', 'color-mix(in oklab, var(--studeo-color-field), white 8%)'),
        'the rule of [--studeo-color-surface] reads itself, directly or through other rules',
    ],
    'a knob that no rule reads' => [
        fn (array $s): array => Spec::set($s, 'knobs.'.count($s['knobs']), ['name' => 'derive-unused', 'css' => '--studeo-derive-unused', 'description' => 'x', 'light' => '1', 'dark' => '1']),
        'knob [--studeo-derive-unused] is read by no rule',
    ],
]);

it('gives the text controls a fill of their own, derived from the surface and the text, and a border that is stated against the page, the surface and that fill', function () {
    $tokens = array_column(Spec::data('tokens')['tokens'], null, 'name');
    $components = array_column(Spec::data('components')['components'], null, 'name');
    $spec = tokenTestSpec();

    expect($tokens)->toHaveKey('color-field')
        ->and($tokens['color-field']['derive'])->toContain('var(--studeo-color-surface)')->toContain('var(--studeo-color-text)');

    foreach (['input', 'select', 'code-input'] as $name) {
        expect($components[$name]['tokens'])->toContain('color-field');
    }

    foreach (['color-background', 'color-surface', 'color-field'] as $ground) {
        expect($spec['contrast'][tokenTestPairIndex($spec, 'color-border-strong', $ground)]['minimum'])->toEqual(3);
    }
});

it('states the pairs of colours that are used on the fill of a field', function () {
    $spec = tokenTestSpec();

    foreach ([['color-text', 4.5], ['color-text-muted', 4.5], ['color-danger-text', 4.5], ['color-border-strong', 3], ['color-focus-ring', 3]] as [$foreground, $minimum]) {
        expect($spec['contrast'][tokenTestPairIndex($spec, $foreground, 'color-field')]['minimum'])->toEqual($minimum);
    }
});

it('says that the code input reads the field fill and no longer the surface colour', function () {
    $tokens = array_column(array_column(Spec::data('components')['components'], null, 'name')['code-input']['tokens'] ?? [], null);

    expect($tokens)->toContain('color-field')->and($tokens)->not->toContain('color-surface');
});

it('proves that the text of a badge in the colour of the brand is read on its fill', function () {
    $pairs = array_map(fn (array $pair): string => $pair['foreground'].' on '.$pair['background'], Spec::data('tokens')['contrast']);

    expect($pairs)->toContain('color-accent-text on color-accent-subtle');
});
