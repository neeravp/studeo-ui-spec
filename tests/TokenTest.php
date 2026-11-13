<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Contrast;
use Studeo\UiSpec\Tests\Support\Spec;
use Studeo\UiSpec\Tests\Support\Tokens;

/**
 * @return array{tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>}
 */
function tokenTestSpec(): array
{
    $data = Spec::data('tokens');

    return ['tokens' => $data['tokens'], 'contrast' => $data['contrast']];
}

/**
 * @param  array{tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
 */
function tokenTestIndex(array $spec, string $name): int
{
    return (int) array_search($name, array_column($spec['tokens'], 'name'), true);
}

/**
 * @param  array{tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>}  $spec
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

it('finds no violation in the tokens of the specification', function () {
    expect(Tokens::violations(tokenTestSpec()))->toBe([]);
});

it('computes the contrast ratio of two colours as WCAG 2 defines it', function (string $first, string $second, float $expected) {
    expect(Contrast::ratio($first, $second))->toEqualWithDelta($expected, 0.01);
})->with([
    'black on white' => ['#000000', '#ffffff', 21.0],
    'white on white' => ['#ffffff', '#ffffff', 1.0],
    'a grey that just fails for text' => ['#777777', '#ffffff', 4.48],
    'a grey that just passes for text' => ['#767676', '#ffffff', 4.54],
    'the order of the colours does not matter' => ['#ffffff', '#767676', 4.54],
    'a violet on white' => ['#7c3aed', '#ffffff', 5.70],
]);

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
    'a pair that names an unknown foreground' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.foreground', 'color-missing'),
        'names unknown token [color-missing]',
    ],
    'a pair that names an unknown background' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'color-missing'),
        'names unknown token [color-missing]',
    ],
    'a pair that uses a token that is not a colour' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'space-1'),
        'uses token [space-1] that is not a colour',
    ],
    'a pair that uses a colour with an alpha channel' => [
        fn (array $s): array => Spec::set($s, 'contrast.0.background', 'color-overlay'),
        'uses token [color-overlay] that has an alpha channel',
    ],
    'a pair that fails in the light mode' => [
        fn (array $s): array => Spec::set($s, 'tokens.'.tokenTestIndex($s, 'color-text').'.light', '#e4e4e7'),
        'in the light mode, below 4.5',
    ],
    'a pair that fails in the dark mode' => [
        fn (array $s): array => Spec::set($s, 'tokens.'.tokenTestIndex($s, 'color-text').'.dark', '#27272a'),
        'in the dark mode, below 4.5',
    ],
    'a pair that asks for more than its colours give' => [
        fn (array $s): array => Spec::set($s, 'contrast.'.tokenTestPairIndex($s, 'color-border-strong', 'color-background').'.minimum', 7),
        'contrast pair [color-border-strong on color-background] has a contrast of',
    ],
]);
