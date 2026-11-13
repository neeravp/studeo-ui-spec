<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Neutrality;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * The files that must stay neutral: the manifest, the data files and the schemas it names, the documents README.md, DESIGN.md
 * and SPEC.md, and every document under docs/.
 *
 * @return list<string>
 */
function neutralityTestFiles(): array
{
    $manifest = Spec::manifest()['files'];
    $named = ['spec.json', 'schemas/spec.schema.json'];

    foreach ($manifest as $entry) {
        $named[] = $entry['data'];
        $named[] = $entry['schema'];
    }

    $documents = array_map(fn (string $path): string => 'docs/'.basename($path), glob(Spec::root().'/docs/*.md') ?: []);

    return [...$named, 'README.md', 'DESIGN.md', 'SPEC.md', ...$documents];
}

it('finds no framework, tool or local path in the files of the specification', function () {
    $files = neutralityTestFiles();
    $found = [];

    foreach ($files as $file) {
        $found = [...$found, ...Neutrality::violations($file, (string) file_get_contents(Spec::root().'/'.$file))];
    }

    expect($files)->toContain('README.md', 'DESIGN.md', 'SPEC.md', 'docs/theming.md', 'docs/dark-mode.md', 'tokens.json', 'schemas/tokens.schema.json')
        ->and($found)->toBe([]);
});

it('finds the name or the path that was seeded', function (string $text, string $fragment) {
    expect(implode("\n", Neutrality::violations('the text', $text)))->toContain($fragment);
})->with([
    'a framework' => ['Use Livewire for this.', 'the text names [Livewire]'],
    'a framework in lower case' => ['built on livewire', 'the text names [Livewire]'],
    'a framework of another stack' => ['written for Vue', 'the text names [Vue]'],
    'a framework that serves pages' => ['pages served by Inertia', 'the text names [Inertia]'],
    'the framework of the package' => ['a Laravel route', 'the text names [Laravel]'],
    'a compiler framework' => ['a Svelte store', 'the text names [Svelte]'],
    'a build tool' => ['with Tailwind classes', 'the text names [Tailwind]'],
    'a template language' => ['a Blade component', 'the text names [Blade]'],
    'a library' => ['a React hook', 'the text names [React]'],
    'a script library' => ['with Alpine on the page', 'the text names [Alpine]'],
    'a test tool' => ['run it with Playwright', 'the text names [Playwright]'],
    'a browser test tool' => ['run it with Dusk', 'the text names [Dusk]'],
    'a unit test tool' => ['run it with Pest', 'the text names [Pest]'],
    'a path on a machine' => ['see /Users/ada/code', 'the text holds a local path'],
    'a path in the home folder' => ['see ~/Packages/Identity', 'the text holds a local path'],
]);

it('does not flag a plain word that only holds or equals a name', function (string $text) {
    expect(Neutrality::violations('the text', $text))->toBe([]);
})->with([
    'a word that contains a name' => 'the inertial frame of the reaction and the tailwinds',
    'a plain word in lower case' => 'a blade of grass at dusk',
    'a verb' => 'the page will react to the click',
    'a pest of the garden' => 'the pest of the garden',
    'a capitalised word that only starts with a name' => 'Reactor and Pesticide and Bladerunner',
]);
