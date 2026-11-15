<?php

declare(strict_types=1);

use Studeo\UiSpec\Tests\Support\Spec;

function schemaTestTokenIndex(string $name): int
{
    return (int) array_search($name, array_column(Spec::data('tokens')['tokens'], 'name'), true);
}

it('accepts each file of the specification against its schema', function (string $key) {
    $document = $key === 'spec' ? Spec::manifest() : Spec::data($key);

    expect(Spec::errors($key, $document))->toBe([]);
})->with(['spec', 'components', 'tokens', 'scenarios']);

it('refuses a manifest that breaks the format, at the place of the mistake', function (Closure $break, string $place) {
    expect(implode("\n", Spec::errors('spec', $break(Spec::manifest()))))->toContain($place);
})->with([
    'a version without a patch number' => [fn (array $d): array => Spec::set($d, 'specVersion', '1.0'), '/specVersion:'],
    'a version with a prefix' => [fn (array $d): array => Spec::set($d, 'specVersion', 'v1.0.0'), '/specVersion:'],
    'no tokens file' => [fn (array $d): array => Spec::forget($d, 'files.tokens'), '/files:'],
    'a kind of file the format does not have' => [fn (array $d): array => Spec::set($d, 'files.styles', ['data' => 'styles.json', 'schema' => 'schemas/styles.schema.json']), '/files:'],
    'a data path that leaves the package' => [fn (array $d): array => Spec::set($d, 'files.components.data', '../components.json'), '/files/components/data:'],
    'an absolute data path' => [fn (array $d): array => Spec::set($d, 'files.components.data', '/components.json'), '/files/components/data:'],
    'a schema path that is not a JSON file' => [fn (array $d): array => Spec::set($d, 'files.components.schema', 'schemas/components.schema.txt'), '/files/components/schema:'],
]);

it('refuses a components file that breaks the format, at the place of the mistake', function (Closure $break, string $place) {
    expect(implode("\n", Spec::errors('components', $break(Spec::data('components')))))->toContain($place);
})->with([
    'a name in capitals' => [fn (array $d): array => Spec::set($d, 'components.0.name', 'Button'), '/components/0/name:'],
    'a name with an underscore' => [fn (array $d): array => Spec::set($d, 'components.0.name', 'icon_button'), '/components/0/name:'],
    'no summary' => [fn (array $d): array => Spec::forget($d, 'components.0.summary'), '/components/0:'],
    'an empty summary' => [fn (array $d): array => Spec::set($d, 'components.0.summary', ''), '/components/0/summary:'],
    'a status that does not exist' => [fn (array $d): array => Spec::set($d, 'components.0.status', 'beta'), '/components/0/status:'],
    'a property the format does not have' => [fn (array $d): array => Spec::set($d, 'components.0.example', 'x'), '/components/0:'],
    'no list of props' => [fn (array $d): array => Spec::forget($d, 'components.0.props'), '/components/0:'],
    'a prop of a type that does not exist' => [fn (array $d): array => Spec::set($d, 'components.0.props.0.type', 'slot'), '/components/0/props/0/type:'],
    'an enum prop without values' => [fn (array $d): array => Spec::forget($d, 'components.0.props.0.values'), '/components/0/props/0:'],
    'a prop that is not an enum but has values' => [fn (array $d): array => Spec::set($d, 'components.0.props.3.values', ['yes']), '/components/0/props/3:'],
    'a boolean prop with a text default' => [fn (array $d): array => Spec::set($d, 'components.0.props.3.default', 'no'), '/components/0/props/3/default:'],
    'an enum value in capitals' => [fn (array $d): array => Spec::set($d, 'components.0.props.0.values', ['Primary']), '/components/0/props/0/values/0:'],
    'repeated enum values' => [fn (array $d): array => Spec::set($d, 'components.0.props.0.values', ['primary', 'primary']), '/components/0/props/0/values:'],
    'a slot without the required flag' => [fn (array $d): array => Spec::forget($d, 'components.0.slots.0.required'), '/components/0/slots/0:'],
    'a root attribute that is not a data-studeo attribute' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.root', 'data-button'), '/components/0/attributes/root:'],
    'a state attribute with capitals' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.states.0', 'data-studeo-Loading'), '/components/0/attributes/states/0:'],
    'a state written as an object' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.states.0', ['name' => 'data-studeo-loading']), '/components/0/attributes/states/0:'],
    'a state of a part with capitals' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.parts.0.states', ['data-studeo-Loading']), '/components/0/attributes/parts/0/states/0:'],
    'a state of a part written as an object' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.parts.0.states', [['name' => 'data-studeo-loading']]), '/components/0/attributes/parts/0/states/0:'],
    'a repeated state of a part' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.parts.0.states', ['data-studeo-loading', 'data-studeo-loading']), '/components/0/attributes/parts/0/states:'],
    'a part with a property the format does not have' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.parts.0.example', 'x'), '/components/0/attributes/parts/0:'],
    'a repeated state' => [fn (array $d): array => Spec::set($d, 'components.0.attributes.states', ['data-studeo-loading', 'data-studeo-loading']), '/components/0/attributes/states:'],
    'no vocabulary of states' => [fn (array $d): array => Spec::forget($d, 'states'), '/:'],
    'an empty vocabulary of states' => [fn (array $d): array => Spec::set($d, 'states', []), '/states:'],
    'a state of the vocabulary with capitals' => [fn (array $d): array => Spec::set($d, 'states.0.name', 'data-studeo-Loading'), '/states/0/name:'],
    'no vocabulary of icons' => [fn (array $d): array => Spec::forget($d, 'icons'), '/:'],
    'an icon with capitals' => [fn (array $d): array => Spec::set($d, 'icons.0.name', 'Check'), '/icons/0/name:'],
    'no vocabulary of messages' => [fn (array $d): array => Spec::forget($d, 'messages'), '/:'],
    'an empty vocabulary of messages' => [fn (array $d): array => Spec::set($d, 'messages', []), '/messages:'],
    'a message key without a group' => [fn (array $d): array => Spec::set($d, 'messages.0.key', 'dismiss'), '/messages/0/key:'],
    'a message key with capitals' => [fn (array $d): array => Spec::set($d, 'messages.0.key', 'Alert.dismiss'), '/messages/0/key:'],
    'a message without text' => [fn (array $d): array => Spec::forget($d, 'messages.0.text'), '/messages/0:'],
    'a message with an empty text' => [fn (array $d): array => Spec::set($d, 'messages.0.text', ''), '/messages/0/text:'],
    'a message without a description' => [fn (array $d): array => Spec::forget($d, 'messages.0.description'), '/messages/0:'],
    'a message with a property the format does not have' => [fn (array $d): array => Spec::set($d, 'messages.0.example', 'x'), '/messages/0:'],
    'a placeholder with capitals' => [fn (array $d): array => Spec::set($d, 'messages.0.placeholders', ['What']), '/messages/0/placeholders/0:'],
    'a repeated placeholder' => [fn (array $d): array => Spec::set($d, 'messages.0.placeholders', ['what', 'what']), '/messages/0/placeholders:'],
    'a plural without the form for one' => [fn (array $d): array => Spec::set($d, 'messages.0.plural', ['by' => 'total']), '/messages/0/plural:'],
    'a plural without the placeholder that counts' => [fn (array $d): array => Spec::set($d, 'messages.0.plural', ['one' => 'One']), '/messages/0/plural:'],
    'a plural with a property the format does not have' => [fn (array $d): array => Spec::set($d, 'messages.0.plural', ['by' => 'total', 'one' => 'One', 'other' => 'Many']), '/messages/0/plural:'],
    'a message of a component that is not a key' => [fn (array $d): array => Spec::set($d, 'components.0.messages', ['Dismiss']), '/components/0/messages/0:'],
    'a repeated message of a component' => [fn (array $d): array => Spec::set($d, 'components.0.messages', ['alert.dismiss', 'alert.dismiss']), '/components/0/messages:'],
    'a variable that is not a variable of the specification' => [fn (array $d): array => Spec::set($d, 'components.0.variables', [['name' => '--other', 'description' => 'x']]), '/components/0/variables/0/name:'],
    'a variable without a description' => [fn (array $d): array => Spec::set($d, 'components.0.variables', [['name' => '--studeo-other']]), '/components/0/variables/0:'],
    'a variable with a property the format does not have' => [fn (array $d): array => Spec::set($d, 'components.0.variables', [['name' => '--studeo-other', 'description' => 'x', 'example' => 'y']]), '/components/0/variables/0:'],
    'no target for the attributes' => [fn (array $d): array => Spec::forget($d, 'components.0.attributesTarget'), '/components/0:'],
    'a target for the attributes that is neither root nor an attribute' => [fn (array $d): array => Spec::set($d, 'components.0.attributesTarget', 'button'), '/components/0/attributesTarget:'],
    'no statement about script' => [fn (array $d): array => Spec::forget($d, 'components.0.script'), '/components/0:'],
    'a need for script that does not exist' => [fn (array $d): array => Spec::set($d, 'components.0.script.need', 'maybe'), '/components/0/script/need:'],
    'a reason for no script' => [fn (array $d): array => Spec::set($d, 'components.0.script.reason', 'Because.'), '/components/0/script/reason:'],
    'script without a reason' => [fn (array $d): array => Spec::set($d, 'components.0.script', ['need' => 'optional', 'reason' => null]), '/components/0/script/reason:'],
    'no fixtures' => [fn (array $d): array => Spec::forget($d, 'components.0.fixtures'), '/components/0:'],
    'an empty list of fixtures' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures', []), '/components/0/fixtures:'],
    'a fixture without a description' => [fn (array $d): array => Spec::forget($d, 'components.0.fixtures.0.description'), '/components/0/fixtures/0:'],
    'a fixture prop that is a list of booleans' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.props.icon', [true]), '/components/0/fixtures/0/props/icon:'],
    'a fixture prop that is a list with an option without a label' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.props.icon', [['value' => 'a']]), '/components/0/fixtures/0/props/icon:'],
    'a fixture prop that is a list with an option that has another field' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.props.icon', [['value' => 'a', 'label' => 'A', 'group' => 'x']]), '/components/0/fixtures/0/props/icon:'],
    'a list prop with a text default' => [fn (array $d): array => Spec::set(Spec::set($d, 'components.0.props.3.type', 'list'), 'components.0.props.3.default', 'a'), '/components/0/props/3/default:'],
    'a fixture slot that is a number' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.slots.default', 3), '/components/0/fixtures/0/slots/default:'],
    'a fixture slot that names a fixture badly' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.slots.default', ['fixture' => 'Button']), '/components/0/fixtures/0/slots/default/fixture:'],
    'a fixture slot that is an empty list' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.slots.default', []), '/components/0/fixtures/0/slots/default:'],
    'a fixture slot that is a list of text' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.slots.default', ['Save']), '/components/0/fixtures/0/slots/default:'],
    'a fixture slot that is a list with a badly named fixture' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.slots.default', [['fixture' => 'Button']]), '/components/0/fixtures/0/slots/default:'],
    'a fixture that is held without a slot' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.in', ['fixture' => 'table.bare']), '/components/0/fixtures/0/in:'],
    'a fixture that is held by a badly named fixture' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.in', ['fixture' => 'Table', 'slot' => 'head']), '/components/0/fixtures/0/in/fixture:'],
    'a fixture that is held in a slot with capitals' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.in', ['fixture' => 'table.bare', 'slot' => 'Head']), '/components/0/fixtures/0/in/slot:'],
    'a fixture that is held and says something else about it' => [fn (array $d): array => Spec::set($d, 'components.0.fixtures.0.in', ['fixture' => 'table.bare', 'slot' => 'head', 'extra' => 1]), '/components/0/fixtures/0/in:'],
    'no role in the accessibility contract' => [fn (array $d): array => Spec::forget($d, 'components.0.accessibility.role'), '/components/0/accessibility:'],
    'a label source that does not exist' => [fn (array $d): array => Spec::set($d, 'components.0.accessibility.label', 'guess'), '/components/0/accessibility/label:'],
    'an ARIA entry that is not an ARIA attribute' => [fn (array $d): array => Spec::set($d, 'components.0.accessibility.aria.0.attribute', 'role'), '/components/0/accessibility/aria/0/attribute:'],
    'a keyboard entry without behaviour' => [fn (array $d): array => Spec::forget($d, 'components.0.accessibility.keyboard.0.behavior'), '/components/0/accessibility/keyboard/0:'],
    'a token name in capitals' => [fn (array $d): array => Spec::set($d, 'components.0.tokens.0', 'Color'), '/components/0/tokens/0:'],
    'a repeated token' => [fn (array $d): array => Spec::set($d, 'components.0.tokens', ['color-accent', 'color-accent']), '/components/0/tokens:'],
    'no components' => [fn (array $d): array => Spec::set($d, 'components', []), '/components:'],
]);

it('refuses a tokens file that breaks the format, at the place of the mistake', function (Closure $break, string $place) {
    expect(implode("\n", Spec::errors('tokens', $break(Spec::data('tokens')))))->toContain($place);
})->with([
    'a colour without a dark value' => [fn (array $d): array => Spec::forget($d, 'tokens.0.dark'), '/tokens/0:'],
    'a colour with a single value' => [fn (array $d): array => Spec::set($d, 'tokens.0.value', '#ffffff'), '/tokens/0:'],
    'a radius with a light value' => [fn (array $d): array => Spec::set($d, 'tokens.'.schemaTestTokenIndex('radius-control').'.light', '#ffffff'), '/tokens/'.schemaTestTokenIndex('radius-control').':'],
    'a radius without a value' => [fn (array $d): array => Spec::forget($d, 'tokens.'.schemaTestTokenIndex('radius-control').'.value'), '/tokens/'.schemaTestTokenIndex('radius-control').':'],
    'a light value that is the name of a colour' => [fn (array $d): array => Spec::set($d, 'tokens.0.light', 'red'), '/tokens/0/light:'],
    'a light value in short hex' => [fn (array $d): array => Spec::set($d, 'tokens.0.light', '#fff'), '/tokens/0/light:'],
    'a dark value in capitals' => [fn (array $d): array => Spec::set($d, 'tokens.0.dark', '#7C3AED'), '/tokens/0/dark:'],
    'no list of contrast pairs' => [fn (array $d): array => Spec::forget($d, 'contrast'), '/:'],
    'no contrast pairs' => [fn (array $d): array => Spec::set($d, 'contrast', []), '/contrast:'],
    'a contrast minimum that is not in the list' => [fn (array $d): array => Spec::set($d, 'contrast.0.minimum', 4), '/contrast/0/minimum:'],
    'a contrast pair without a foreground' => [fn (array $d): array => Spec::forget($d, 'contrast.0.foreground'), '/contrast/0:'],
    'a contrast pair with a property the format does not have' => [fn (array $d): array => Spec::set($d, 'contrast.0.mode', 'dark'), '/contrast/0:'],
    'a variable without the prefix' => [fn (array $d): array => Spec::set($d, 'tokens.0.css', '--accent'), '/tokens/0/css:'],
    'a category that does not exist' => [fn (array $d): array => Spec::set($d, 'tokens.0.category', 'motion'), '/tokens/0/category:'],
    'a name in capitals' => [fn (array $d): array => Spec::set($d, 'tokens.0.name', 'Color-accent'), '/tokens/0/name:'],
    'an empty description' => [fn (array $d): array => Spec::set($d, 'tokens.0.description', ''), '/tokens/0/description:'],
    'a property the format does not have' => [fn (array $d): array => Spec::set($d, 'tokens.0.unit', 'px'), '/tokens/0:'],
    'no tokens' => [fn (array $d): array => Spec::set($d, 'tokens', []), '/tokens:'],
]);

it('refuses a scenarios file that breaks the format, at the place of the mistake', function (Closure $break, string $place) {
    expect(implode("\n", Spec::errors('scenarios', $break(Spec::data('scenarios')))))->toContain($place);
})->with([
    'an action that is not in the list' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.action', 'visit'), '/scenarios/0/steps/0/action:'],
    'a target by class' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.target', ['class' => '.button']), '/scenarios/0/steps/0/target:'],
    'a target by id' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.target', ['id' => 'save']), '/scenarios/0/steps/0/target:'],
    'a target by selector' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.target', ['css' => 'button']), '/scenarios/0/steps/0/target:'],
    'a target by role without a name' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.target', ['role' => 'button']), '/scenarios/0/steps/0/target:'],
    'a target by an attribute that is not data-studeo' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.0.target', ['attribute' => 'class']), '/scenarios/0/steps/0/target/attribute:'],
    'a click without a target' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'click']]), '/scenarios/0/steps/0:'],
    'a fill without a value' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'fill', 'target' => ['role' => 'textbox', 'name' => 'Name']]]), '/scenarios/0/steps/0:'],
    'a press without a key' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'press']]), '/scenarios/0/steps/0:'],
    'an expectText without a text' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'expectText', 'target' => ['role' => 'status', 'name' => 'Result']]]), '/scenarios/0/steps/0:'],
    'an expectNoAttribute without an attribute' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'expectNoAttribute', 'target' => ['attribute' => 'data-studeo-button']]]), '/scenarios/0/steps/0:'],
    'an expectNoAttribute with a value' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'expectNoAttribute', 'target' => ['attribute' => 'data-studeo-button'], 'attribute' => 'data-studeo-loading', 'value' => 'true']]), '/scenarios/0/steps/0:'],
    'an expectNoAttribute that checks the class attribute' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', [['action' => 'expectNoAttribute', 'target' => ['attribute' => 'data-studeo-button'], 'attribute' => 'class']]), '/scenarios/0/steps/0/attribute:'],
    'a check of the class attribute' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps.1.attribute', 'class'), '/scenarios/0/steps/1/attribute:'],
    'a fixture without a case' => [fn (array $d): array => Spec::set($d, 'scenarios.0.fixture', 'button'), '/scenarios/0/fixture:'],
    'a fixture in capitals' => [fn (array $d): array => Spec::set($d, 'scenarios.0.fixture', 'Button.loading'), '/scenarios/0/fixture:'],
    'no steps' => [fn (array $d): array => Spec::set($d, 'scenarios.0.steps', []), '/scenarios/0/steps:'],
    'no summary' => [fn (array $d): array => Spec::forget($d, 'scenarios.0.summary'), '/scenarios/0:'],
    'a property the format does not have' => [fn (array $d): array => Spec::set($d, 'scenarios.0.retries', 3), '/scenarios/0:'],
]);

it('accepts a list prop, and fixtures that give it plain values or options', function (array $value) {
    $document = Spec::set(Spec::data('components'), 'components.0.fixtures.0.props.icon', $value);

    expect(Spec::errors('components', $document))->toBe([]);
})->with([
    'whole numbers' => [[10, 25, 50]],
    'texts' => [['Editor', 'Viewer']],
    'options' => [[['value' => 'admin', 'label' => 'Administrator'], ['value' => 7, 'label' => 'Seven']]],
    'a mix of a text and an option' => [['Editor', ['value' => 'admin', 'label' => 'Administrator']]],
    'no items' => [[]],
]);

it('accepts every action of the closed list', function (array $step) {
    $document = Spec::set(Spec::data('scenarios'), 'scenarios.0.steps', [$step]);

    expect(Spec::errors('scenarios', $document))->toBe([]);
})->with([
    'click' => [['action' => 'click', 'target' => ['role' => 'button', 'name' => 'Save']]],
    'focus' => [['action' => 'focus', 'target' => ['role' => 'button', 'name' => 'Save']]],
    'expectVisible' => [['action' => 'expectVisible', 'target' => ['role' => 'button', 'name' => 'Save']]],
    'expectHidden' => [['action' => 'expectHidden', 'target' => ['attribute' => 'data-studeo-alert']]],
    'expectFocus' => [['action' => 'expectFocus', 'target' => ['role' => 'button', 'name' => 'Save']]],
    'fill' => [['action' => 'fill', 'target' => ['role' => 'textbox', 'name' => 'Name'], 'value' => 'Ada']],
    'fill with an empty value' => [['action' => 'fill', 'target' => ['role' => 'textbox', 'name' => 'Name'], 'value' => '']],
    'press' => [['action' => 'press', 'key' => 'Enter']],
    'expectText' => [['action' => 'expectText', 'target' => ['role' => 'status', 'name' => 'Result'], 'text' => 'Saved']],
    'expectAttribute' => [['action' => 'expectAttribute', 'target' => ['role' => 'button', 'name' => 'Save'], 'attribute' => 'disabled']],
    'expectNoAttribute' => [['action' => 'expectNoAttribute', 'target' => ['attribute' => 'data-studeo-switch'], 'attribute' => 'data-studeo-checked']],
    'expectAttribute with a value' => [['action' => 'expectAttribute', 'target' => ['role' => 'button', 'name' => 'Save'], 'attribute' => 'aria-busy', 'value' => 'true']],
]);
