<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Spec;

it('declares a component that the screens need', function (string $name) {
    expect(array_column(Spec::data('components')['components'], 'name'))->toContain($name);
})->with([
    'button',
    'icon',
    'label',
    'error',
    'field',
    'input',
    'checkbox',
    'switch',
    'code-input',
    'heading',
    'text',
    'link',
    'alert',
    'card',
    'table',
    'table-column',
    'table-row',
    'table-cell',
    'data-table',
    'dialog',
    'toast',
    'toast-region',
]);

it('gives every component at least one scenario', function () {
    $components = array_column(Spec::data('components')['components'], 'name');
    $covered = array_column(Spec::data('scenarios')['scenarios'], 'component');

    expect(array_values(array_diff($components, $covered)))->toBe([]);
});

it('puts the disabled state of a data table on its paging controls and not on the table', function () {
    $table = array_column(Spec::data('components')['components'], null, 'name')['data-table'];
    $parts = array_column($table['attributes']['parts'], null, 'name');

    expect($table['attributes']['states'])->not->toContain('data-studeo-disabled');
    expect($table['attributes']['states'])->toContain('data-studeo-loading');

    foreach (['first', 'previous', 'next', 'last'] as $control) {
        expect($parts["data-studeo-data-table-{$control}"]['states'] ?? [])->toBe(['data-studeo-disabled']);
    }

    expect($parts['data-studeo-data-table-search']['states'] ?? [])->toBe([]);
});

it('says which addresses a link uses, and that the words of an external link can be translated', function () {
    $link = array_column(Spec::data('components')['components'], null, 'name')['link'];
    $href = array_column($link['props'], null, 'name')['href']['description'];
    $external = array_column($link['attributes']['parts'], null, 'name')['data-studeo-link-external']['description'];

    foreach (['relative', 'http', 'https', 'mailto', 'tel', 'runs a script'] as $word) {
        expect($href)->toContain($word);
    }

    expect($external)->toContain('translate')->toContain('Opens in a new tab');
});

it('says what script a component needs, in the cases that the scenarios exercise', function (string $name, string $fragment) {
    $component = array_column(Spec::data('components')['components'], null, 'name')[$name];

    expect($component['script']['need'])->toBe('optional');
    expect($component['script']['reason'])->toContain($fragment);
})->with([
    'a switch keeps its state attribute in step by script' => ['switch', 'state attribute of the root in step'],
    'a checkbox keeps its state attributes in step by script' => ['checkbox', 'state attributes of the root in step'],
    'a dialog is made modal by script' => ['dialog', 'Making the dialog modal'],
    'a data table sends a page size at once by script' => ['data-table', 'new page size'],
    'a loading switch ignores the keyboard by script' => ['switch', 'ignored by script'],
    'a mixed checkbox is drawn empty without script' => ['checkbox', 'drawn empty'],
    'the reveal button does nothing without script' => ['input', 'reveal button does nothing'],
    'the dismiss button does nothing without script' => ['alert', 'dismiss button does nothing'],
    'an open dialog is not modal without script' => ['dialog', 'is not modal'],
]);

it('states the measure of each size of a component in rem, and the measures grow with the size', function (string $component, string $dimension, array $expected) {
    $components = array_column(Spec::data('components')['components'], null, 'name');
    $size = array_column($components[$component]['props'], null, 'name')['size'];
    $measures = array_column($size['measures'], null, 'value');

    expect(array_column($size['measures'], 'dimension'))->each->toBe($dimension);
    expect(array_map(fn (string $value): string => $measures[$value]['length'], $size['values']))->toBe($expected);

    $lengths = array_map(fn (string $length): float => (float) $length, $expected);
    $sorted = $lengths;
    sort($sorted);

    expect($lengths)->toBe($sorted)->and(count(array_unique($lengths)))->toBe(count($lengths));
})->with([
    'the button is as high as the control of its size' => ['button', 'height', ['1.75rem', '2.25rem', '2.75rem']],
    'the input is as high as the control of its size' => ['input', 'height', ['1.75rem', '2.25rem', '2.75rem']],
    'the select is as high as the control of its size' => ['select', 'height', ['1.75rem', '2.25rem', '2.75rem']],
    'the icon is as large as its size' => ['icon', 'size', ['0.875rem', '1rem', '1.25rem']],
    'the spinner is as large as its size' => ['spinner', 'size', ['0.875rem', '1rem', '1.25rem']],
    'the heading is drawn in the font size of its size' => ['heading', 'font-size', ['1rem', '1.125rem', '1.25rem', '1.5rem']],
    'the text is drawn in the font size of its size' => ['text', 'font-size', ['0.875rem', '1rem', '1.125rem']],
    'the dialog is as wide as its size' => ['dialog', 'width', ['28rem', '36rem', '48rem']],
]);

it('keeps layout values out of the tokens: a component names colours, radii and font families only', function () {
    foreach (Spec::data('components')['components'] as $component) {
        foreach ($component['tokens'] as $name) {
            expect($name)->toMatch('/^(color|radius|font-family)-/');
        }
    }
});

it('says which control a field ties, and which controls are not put in a field', function () {
    $slots = array_column(array_column(Spec::data('components')['components'], null, 'name')['field']['slots'], null, 'name');

    expect($slots['default']['description'])->toContain('first form control')->toContain('draws its own label');
});

it('lets a link look like a button and says what a link with no address is', function () {
    $link = array_column(Spec::data('components')['components'], null, 'name')['link'];
    $props = array_column($link['props'], null, 'name');

    expect($props['variant']['values'])->toContain('button');
    expect($props['href']['required'] ?? false)->toBeFalse();
    expect($props['href']['description'])->toContain('disabled link');
    expect($link['attributes']['states'])->toContain('data-studeo-disabled');
    expect(array_column($link['accessibility']['aria'], 'attribute'))->toContain('aria-disabled');

    foreach (['color-surface', 'color-border-strong', 'color-text-muted'] as $token) {
        expect($link['tokens'])->toContain($token);
    }

    foreach (['button', 'disabled', 'button-disabled'] as $case) {
        expect(array_column($link['fixtures'], 'name'))->toContain($case);
    }
});

it('builds the paging controls of a data table from links', function () {
    $table = array_column(Spec::data('components')['components'], null, 'name')['data-table'];

    expect($table['composes'])->toContain('link')->not->toContain('button');

    foreach (['first', 'previous', 'next', 'last'] as $control) {
        $part = array_column($table['attributes']['parts'], null, 'name')["data-studeo-data-table-{$control}"];

        expect($part['description'])->toContain('a disabled link');
    }
});

it('has a scenario that the keyboard cannot change a loading switch', function () {
    $scenario = array_column(Spec::data('scenarios')['scenarios'], null, 'id')['switch-ignores-the-keyboard-while-it-is-loading'];

    expect($scenario['fixture'])->toBe('switch.loading');
    expect(array_column($scenario['steps'], 'action'))->toBe(['focus', 'press', 'expectNoAttribute']);
    expect($scenario['steps'][2]['attribute'])->toBe('data-studeo-checked');
});

it('has a scenario that checks the description and the invalidity of the control of a field together', function () {
    $scenario = array_column(Spec::data('scenarios')['scenarios'], null, 'id')['field-describes-and-invalidates-the-control-together'];

    expect($scenario['fixture'])->toBe('field.invalid');
    expect(array_column($scenario['steps'], 'attribute'))->toBe(['aria-describedby', 'aria-invalid']);
    expect(array_unique(array_map(fn (array $step): string => json_encode($step['target']), $scenario['steps'])))->toHaveCount(1);
});

it('declares the select as a native control that picks one option from a list', function () {
    $select = array_column(Spec::data('components')['components'], null, 'name')['select'];
    $props = array_column($select['props'], null, 'name');

    expect($select['accessibility']['role'])->toBe('combobox');
    expect($select['script']['need'])->toBe('none');
    expect($select['composes'])->toBe(['field', 'label', 'error', 'icon']);
    expect($select['attributesTarget'])->toBe('data-studeo-select-control');
    expect($props['options']['type'])->toBe('list');
    expect($props['options']['required'])->toBeTrue();
    expect($select['attributes']['states'])->toBe(['data-studeo-invalid', 'data-studeo-disabled', 'data-studeo-loading']);
    expect(array_column($select['attributes']['parts'], 'name'))->toBe(['data-studeo-select-control', 'data-studeo-select-chevron', 'data-studeo-select-error']);
});

it('shows the options of a select as options with labels and as plain whole numbers', function () {
    $fixtures = array_column(array_column(Spec::data('components')['components'], null, 'name')['select']['fixtures'], null, 'name');

    expect($fixtures['default']['props']['options'][0])->toBe(['value' => 'admin', 'label' => 'Administrator']);
    expect($fixtures['plain']['props']['options'])->toBe([10, 25, 50, 100]);
    expect($fixtures['plain']['props']['value'])->toBe('25');
});

it('offers the page sizes of a data table through its select, from a list of whole numbers', function () {
    $table = array_column(Spec::data('components')['components'], null, 'name')['data-table'];
    $props = array_column($table['props'], null, 'name');
    $parts = array_column($table['attributes']['parts'], null, 'name');

    expect($props['per-page-options']['type'])->toBe('list');
    expect($props['per-page-options']['default'])->toBe([10, 25, 50, 100]);
    expect($props['per-page-options']['description'])->toContain('10, 25, 50 and 100');
    expect($table['composes'])->toContain('select');
    expect($parts['data-studeo-data-table-per-page']['description'])->toContain('is a select')->toContain('per-page-options');
});

it('names the fields that a data table sends', function () {
    $table = array_column(Spec::data('components')['components'], null, 'name')['data-table'];
    $parts = array_column($table['attributes']['parts'], null, 'name');
    $props = array_column($table['props'], null, 'name');

    expect($parts['data-studeo-data-table-search']['description'])->toContain('name search');
    expect($parts['data-studeo-data-table-per-page']['description'])->toContain('name per_page');
    expect($props['sort']['description'])->toContain('name sort');
});

it('builds a table from a column, a row and a cell, each shown in the fixture that holds it', function () {
    $components = array_column(Spec::data('components')['components'], null, 'name');

    expect($components['table']['composes'])->toBe(['table-column', 'table-row', 'table-cell']);

    $hosts = ['table-column' => ['table.bare', 'head'], 'table-row' => ['table.bare', 'default'], 'table-cell' => ['table-row.bare', 'default']];

    foreach ($hosts as $name => [$fixture, $slot]) {
        foreach ($components[$name]['fixtures'] as $case) {
            expect($case['in'] ?? null)->toBe(['fixture' => $fixture, 'slot' => $slot]);
        }
    }
});

it('lets an author give classes to the control of a component that wraps one', function (string $name) {
    $component = array_column(Spec::data('components')['components'], null, 'name')[$name];
    $prop = array_column($component['props'], null, 'name')['control-class'];

    expect($prop['type'])->toBe('class');
    expect($component['attributesTarget'])->not->toBe('root');
})->with(['input', 'checkbox', 'switch', 'code-input']);

it('says how a toast is raised, by an event that every stack can send', function () {
    $reason = array_column(Spec::data('components')['components'], null, 'name')['toast-region']['script']['reason'];

    foreach (['studeo:toast', 'window', 'variant', 'title', 'message', 'duration'] as $word) {
        expect($reason)->toContain($word);
    }
});

it('has a fixture of a data table with no rows and no words of its own, and a scenario that expects the words of the message', function () {
    $components = Spec::data('components');
    $table = array_column($components['components'], null, 'name')['data-table'];
    $fixture = array_column($table['fixtures'], null, 'name')['no-results'] ?? [];
    $scenario = array_column(Spec::data('scenarios')['scenarios'], null, 'id')['data-table-says-that-there-are-no-results-in-its-own-words'] ?? [];
    $message = array_column($components['messages'], null, 'key')['data-table.empty'];

    expect($fixture['props']['total'] ?? null)->toBe(0);
    expect($fixture['slots'] ?? [])->not->toHaveKey('empty');
    expect($scenario['fixture'] ?? null)->toBe('data-table.no-results');
    expect($scenario['steps'][0]['target'] ?? null)->toBe(['attribute' => 'data-studeo-data-table-empty']);
    expect($scenario['steps'][0]['text'] ?? null)->toBe($message['text']);
});
