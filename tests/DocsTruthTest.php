<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\DocsTruth;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * The text of the documents that the rules read, by path.
 *
 * @return array<string, string>
 */
function docsTruthDocuments(): array
{
    $paths = ['README.md', 'DESIGN.md', 'SPEC.md', 'CHANGELOG.md'];

    foreach (glob(Spec::root().'/docs/*.md') ?: [] as $file) {
        $paths[] = 'docs/'.basename($file);
    }

    $documents = [];

    foreach ($paths as $path) {
        $documents[$path] = (string) file_get_contents(Spec::root().'/'.$path);
    }

    return $documents;
}

/**
 * @return array{components: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>, tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>, actions: list<string>, documents: array<string, string>}
 */
function docsTruthSpec(): array
{
    $actions = [];

    foreach (Spec::decode('schemas/scenarios.schema.json')['$defs']['step']['oneOf'] as $shape) {
        $action = $shape['properties']['action'];

        foreach ($action['enum'] ?? [$action['const']] as $name) {
            $actions[] = $name;
        }
    }

    return [
        'components' => Spec::data('components')['components'],
        'states' => Spec::data('components')['states'],
        'icons' => Spec::data('components')['icons'],
        'tokens' => Spec::data('tokens')['tokens'],
        'contrast' => Spec::data('tokens')['contrast'],
        'actions' => $actions,
        'documents' => docsTruthDocuments(),
    ];
}

/**
 * @param  array{components: list<array<string, mixed>>, states: list<array<string, mixed>>, icons: list<array<string, mixed>>, tokens: list<array<string, mixed>>, contrast: list<array<string, mixed>>, actions: list<string>, documents: array<string, string>}  $spec
 * @return list<string>
 */
function docsTruthViolations(array $spec): array
{
    $documents = $spec['documents'];
    unset($spec['documents']);

    return DocsTruth::violations($spec, $documents, fn (string $path): bool => file_exists(Spec::root().'/'.$path));
}

/**
 * The text of a document without the lines that start with a given text.
 */
function docsTruthWithout(string $text, string $start): string
{
    return implode("\n", array_filter(explode("\n", $text), fn (string $line): bool => ! str_starts_with($line, $start)));
}

it('finds that the documents are true to the data files', function () {
    expect(docsTruthViolations(docsTruthSpec()))->toBe([]);
});

it('finds the mistake that was seeded', function (Closure $seed, string $fragment) {
    $spec = docsTruthSpec();
    $spec['documents'] = $seed($spec['documents']);

    expect(implode("\n", docsTruthViolations($spec)))->toContain($fragment);
})->with([
    'a component whose heading is missing' => [
        fn (array $d): array => [...$d, 'docs/components.md' => docsTruthWithout($d['docs/components.md'], '## card')],
        'docs/components.md has no heading for component [card]',
    ],
    'a heading for a component that does not exist' => [
        fn (array $d): array => [...$d, 'docs/components.md' => $d['docs/components.md']."\n## ghost\n"],
        'docs/components.md has a heading [ghost] for a component that does not exist',
    ],
    'components in another order' => [
        fn (array $d): array => [...$d, 'docs/components.md' => str_replace(['## button', '## icon', '## TEMPORARY'], ['## TEMPORARY', '## button', '## icon'], $d['docs/components.md'])],
        'docs/components.md lists the components in a different order from components.json',
    ],
    'a component without a row in the index' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '| [`card`]')],
        'SPEC.md has no row for component [card]',
    ],
    'a state without a row' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '| `data-studeo-open` |')],
        'SPEC.md has no row for state [data-studeo-open]',
    ],
    'an icon without a row' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '| `warning` |')],
        'SPEC.md has no row for icon [warning]',
    ],
    'an action that is not listed' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '- `press`')],
        'SPEC.md has no row for action [press]',
    ],
    'a row of the list of actions that is missing while its meaning stays' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '- `press` (')],
        'SPEC.md has no row for action [press]',
    ],
    'an action without a meaning' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '- `expectText`:')],
        'SPEC.md has no meaning for action [expectText]',
    ],
    'the section on what a step means is missing' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '### What a step means')],
        'SPEC.md has no section [What a step means]',
    ],
    'the section on finding the target of a step is missing' => [
        fn (array $d): array => [...$d, 'SPEC.md' => docsTruthWithout($d['SPEC.md'], '### Finding the target of a step')],
        'SPEC.md has no section [Finding the target of a step]',
    ],
    'a token without a row' => [
        fn (array $d): array => [...$d, 'docs/theming.md' => docsTruthWithout($d['docs/theming.md'], '| `radius-control` |')],
        'docs/theming.md has no row for token [radius-control]',
    ],
    'a measure without a row' => [
        fn (array $d): array => [...$d, 'docs/components.md' => docsTruthWithout($d['docs/components.md'], '| `button.size` | `sm` |')],
        'docs/components.md has no row for measure [button.size.sm]',
    ],
    'a token whose variable is written wrong' => [
        fn (array $d): array => [...$d, 'docs/theming.md' => str_replace('`--studeo-radius-control`', '`--studeo-radius-controls`', $d['docs/theming.md'])],
        'docs/theming.md has no row for token [radius-control]',
    ],
    'a pair of colours without a row' => [
        fn (array $d): array => [...$d, 'docs/theming.md' => docsTruthWithout($d['docs/theming.md'], '| `color-text` | `color-background` |')],
        'docs/theming.md has no row for the pair [color-text on color-background]',
    ],
    'an unfinished placeholder' => [
        fn (array $d): array => [...$d, 'README.md' => $d['README.md']."\n<!-- TODO(docs): finish this -->\n"],
        'has an unfinished placeholder',
    ],
    'a link to a file that does not exist' => [
        fn (array $d): array => [...$d, 'README.md' => $d['README.md']."\n[missing](MISSING.md)\n"],
        'README.md links to [MISSING.md], which does not exist',
    ],
    'a link to a heading that does not exist' => [
        fn (array $d): array => [...$d, 'SPEC.md' => str_replace('(docs/components.md#button)', '(docs/components.md#buton)', $d['SPEC.md'])],
        'SPEC.md links to [docs/components.md#buton], which has no such heading',
    ],
]);
