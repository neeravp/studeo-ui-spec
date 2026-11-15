<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Messages;
use Studeo\UiSpec\Tests\Support\Spec;

/**
 * @return array{messages: list<array<string, mixed>>, components: list<array<string, mixed>>, tokens: list<array<string, mixed>>}
 */
function messageTestSpec(): array
{
    $components = Spec::data('components');

    return ['messages' => $components['messages'], 'components' => $components['components'], 'tokens' => Spec::data('tokens')['tokens']];
}

/**
 * @param  array{messages: list<array<string, mixed>>, components: list<array<string, mixed>>, tokens: list<array<string, mixed>>}  $spec
 */
function messageTestComponent(array $spec, string $name): int
{
    return (int) array_search($name, array_column($spec['components'], 'name'), true);
}

/**
 * @param  array{messages: list<array<string, mixed>>, components: list<array<string, mixed>>, tokens: list<array<string, mixed>>}  $spec
 */
function messageTestMessage(array $spec, string $key): int
{
    return (int) array_search($key, array_column($spec['messages'], 'key'), true);
}

it('finds no violation in the messages and the variables of the specification', function () {
    expect(Messages::violations(messageTestSpec()))->toBe([]);
});

it('finds the mistake that was seeded', function (Closure $seed, string $fragment) {
    $violations = Messages::violations($seed(messageTestSpec()));

    expect(implode("\n", $violations))->toContain($fragment);
})->with([
    'a message declared twice' => [
        fn (array $s): array => Spec::set($s, 'messages.'.count($s['messages']), $s['messages'][0]),
        'message [alert.dismiss] is declared twice',
    ],
    'a component that writes a message that does not exist' => [
        fn (array $s): array => Spec::set($s, 'components.'.messageTestComponent($s, 'alert').'.messages', ['alert.missing']),
        'component [alert] writes unknown message [alert.missing]',
    ],
    'a message that no component writes' => [
        fn (array $s): array => Spec::forget($s, 'components.'.messageTestComponent($s, 'alert').'.messages'),
        'message [alert.dismiss] is written by no component',
    ],
    'a message that two components write' => [
        fn (array $s): array => Spec::set($s, 'components.'.messageTestComponent($s, 'toast').'.messages', ['toast.dismiss', 'alert.dismiss']),
        'message [alert.dismiss] is written by more than one component',
    ],
    'a placeholder that the text uses and the message does not declare' => [
        fn (array $s): array => Spec::set($s, 'messages.0.text', 'Dismiss :what'),
        'message [alert.dismiss] uses the placeholder [:what] in its text and does not declare it',
    ],
    'a placeholder that is declared and that the text does not use' => [
        fn (array $s): array => Spec::set($s, 'messages.0.placeholders', ['what']),
        'message [alert.dismiss] declares the placeholder [what] that its text does not use',
    ],
    'a form for one with other placeholders than the text' => [
        fn (array $s): array => Spec::set($s, 'messages.'.messageTestMessage($s, 'data-table.summary').'.plural.one', ':total result'),
        'message [data-table.summary] has a form for one with other placeholders than its text',
    ],
    'a count by a placeholder that is not declared' => [
        fn (array $s): array => Spec::set($s, 'messages.'.messageTestMessage($s, 'data-table.summary').'.plural.by', 'amount'),
        'message [data-table.summary] counts by [amount], which it does not declare',
    ],
    'a variable that two components declare' => [
        fn (array $s): array => Spec::set($s, 'components.'.messageTestComponent($s, 'table').'.variables', $s['components'][messageTestComponent($s, 'code-input')]['variables']),
        'variable [--studeo-code-length] is declared twice',
    ],
    'a variable that is the variable of a token' => [
        fn (array $s): array => Spec::set($s, 'components.'.messageTestComponent($s, 'code-input').'.variables.0.name', '--studeo-color-accent'),
        'variable [--studeo-color-accent] of component [code-input] is the variable of token [color-accent]',
    ],
]);
