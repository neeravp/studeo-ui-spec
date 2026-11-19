# Specification

The observable contract. Where this and the data files disagree, the data files are right and this document is wrong.

## 1. Files

- `spec.json` is the manifest. It names the data file and the schema of each kind and carries the version of the specification.
- `components.json`, `tokens.json` and `scenarios.json` are the data. A schema under `schemas/` describes each of them, in JSON Schema.
- The documents under `docs/` are the prose. The reference pages in them are generated from the data.

A package finds everything from the manifest.

## 2. Components

A component in `components.json` has:

- a name, in lower case, in words joined by hyphens, a summary and a status;
- its props, each with a type (`string`, `boolean`, `integer`, `enum`, `url`, `class`, `icon` or `list`), and its slots; a `list` prop holds a list of items, each a text or a whole number (its own label) or an option with a `value` and a `label`;
- its attributes: a root, its parts and the states it can be in; a state that only a part can be in is listed with that part (the paging controls of a data table can be disabled, the table cannot);
- the element that receives the attributes an author writes on the component;
- its accessibility: the role, how it is named, the keys it answers and the ARIA attributes it sets;
- the script it needs and the reason: `none`, `optional` (it works without script and a named part of its behaviour takes a small one) or `required` (it cannot do its job without script);
- the tokens it reads at least (a UI package may read other tokens of the specification as well) and the components it composes;
- the messages it writes itself, by key, and the CSS variables it sets for its own classes;
- its fixtures: named cases that set props and fill slots. A slot may hold the fixture of another component, or a list of them shown in order. A fixture of a component that cannot stand alone, such as a column, a row or a cell, names the fixture that holds it and the slot it is shown in (`in`); a UI package shows it inside that fixture.

[docs/components.md](docs/components.md) holds the contract of each one. The components, in the order of the file:

<!-- generated:components -->
| Component | Summary | Script |
| --- | --- | --- |
| [`button`](docs/components.md#button) | A control that starts an action. | none |
| [`icon`](docs/components.md#icon) | A small picture that carries a meaning or decorates. | none |
| [`spinner`](docs/components.md#spinner) | A turning picture that shows that something is loading; it stands still when the user asks for less motion. | none |
| [`label`](docs/components.md#label) | The visible name of a control. | none |
| [`error`](docs/components.md#error) | The message that says why a value is refused. | none |
| [`field`](docs/components.md#field) | A control with its label, its hint and its error, tied together. | none |
| [`input`](docs/components.md#input) | A control for a line of text. | optional |
| [`checkbox`](docs/components.md#checkbox) | A control that turns one choice on or off. | optional |
| [`switch`](docs/components.md#switch) | A control that turns a setting on or off at once. | optional |
| [`code-input`](docs/components.md#code-input) | A control for a short code, such as the code of an authenticator app or a recovery code. | none |
| [`heading`](docs/components.md#heading) | A title. Its level in the outline of the page is set apart from its size. | none |
| [`text`](docs/components.md#text) | A paragraph of running text. | none |
| [`link`](docs/components.md#link) | A way to go to another address. It marks the place the user is in. | none |
| [`alert`](docs/components.md#alert) | A message that stays in the page until the situation changes or the user dismisses it. | optional |
| [`card`](docs/components.md#card) | A surface that groups what belongs together: a header, a body and a footer. | none |
| [`table`](docs/components.md#table) | Rows and columns of data, with a caption and a message for when there are no rows. | none |
| [`table-column`](docs/components.md#table-column) | The header of one column. When the list can be sorted by the column it is a link to the address that sorts by it. | none |
| [`table-row`](docs/components.md#table-row) | One row of the body: the cells of one record. | none |
| [`table-cell`](docs/components.md#table-cell) | One cell of a row. | none |
| [`data-table`](docs/components.md#data-table) | A table of a long list that the server pages, sorts and filters, with a search, filters and paging controls. | optional |
| [`dialog`](docs/components.md#dialog) | A window over the page that asks for the user's attention until it is closed. While it is open, the rest of the page cannot be used. | optional |
| [`toast`](docs/components.md#toast) | A short message that appears over the page to confirm something. A message of success or information hides itself after a few seconds. | required |
| [`toast-region`](docs/components.md#toast-region) | The place at the bottom end of the page where toasts appear, one after another. | required |
| [`select`](docs/components.md#select) | A control that picks one value from a list of options. The closed control looks the same everywhere; the open list is the platform's. | none |
<!-- /generated:components -->

### Messages and variables

`components.json` holds the words that components write themselves, in one list at the top of the file. A message has a key (a group and a name with a dot between them, such as `dialog.close`), its text in English, a description of where it is used and, when the text has holes, the placeholders: a hole is written `:name` in the text. A text that depends on a count has the text for every number but one and, in `plural`, the placeholder that holds the count and the text for exactly one. A component lists the keys of the messages that it writes itself, not those of the components it composes, and every message is written by exactly one component. An implementation derives the English words of its components from this list, so that every implementation says the same, and lets a host replace them.

A component may set CSS variables for its own classes. It declares them (`variables`); they are not tokens, they do not follow the token naming and a host does not change them.

## 3. States

A state is a `data-studeo-*` attribute that is present while a component is in that state. A component declares the states it can be in, each from this vocabulary:

<!-- generated:states -->
| State attribute | Meaning |
| --- | --- |
| `data-studeo-loading` | Present while an action is in progress. |
| `data-studeo-disabled` | Present while the component is switched off. |
| `data-studeo-invalid` | Present while the value of the component is refused. |
| `data-studeo-readonly` | Present while the value can be read and selected but not changed. |
| `data-studeo-checked` | Present while a choice is on. |
| `data-studeo-indeterminate` | Present while a choice is neither on nor off. |
| `data-studeo-selected` | Present while an item is picked from a set. |
| `data-studeo-open` | Present while a component that can unfold is unfolded. |
| `data-studeo-current` | Present on the item that stands for the place the user is in. |
<!-- /generated:states -->

## 4. Icons

A component that shows an icon names it from this vocabulary. An implementation provides these icons in any artwork and may provide more:

<!-- generated:icons -->
| Icon | Meaning |
| --- | --- |
| `check` | A tick, for success and for a choice that is on. |
| `chevron-down` | A downward chevron, for a descending order and for something that unfolds. |
| `chevron-up` | An upward chevron, for an ascending order. |
| `close` | A cross, for dismissing and clearing. |
| `eye` | An open eye, for showing a hidden value. |
| `eye-off` | A struck-through eye, for hiding a shown value. |
| `info` | A circled letter i, for information. |
| `minus` | A dash, for a choice that is neither on nor off. |
| `search` | A magnifying glass, for searching. |
| `warning` | A triangle with an exclamation mark, for a warning or an error. |
<!-- /generated:icons -->

## 5. Tokens

`tokens.json` lists every token with its category, its CSS variable and its reference value. A colour is an input (a host sets it, with a light and a dark value), a derived colour (a CSS rule that reads the inputs, the other derived colours and the knobs) or a constant. The file also lists the knobs of the rules, the attribute that scopes a region to its own inputs, and the pairs of colours that are used together with the lowest contrast each must reach, in both modes. [docs/theming.md](docs/theming.md) lists them and says how a host changes them.

## 6. Scenarios

A scenario starts from a fixture, written `<component>.<case>`: an implementation provides a page that shows the component in that case. Its steps use these actions, each with the fields in brackets besides its name:

<!-- generated:actions -->
- `click` (target)
- `focus` (target)
- `expectVisible` (target)
- `expectHidden` (target)
- `expectFocus` (target)
- `fill` (target, value)
- `press` (key)
- `expectText` (target, text)
- `expectAttribute` (target, attribute)
- `expectNoAttribute` (target, attribute)
<!-- /generated:actions -->

A target is found by role and accessible name, or by a `data-studeo-*` attribute. A step that checks an attribute names an ARIA attribute or a state that the component of the scenario declares, or `disabled` where the component has a prop of that name. Waiting is not an action: a time limit is stated in the contract of the component.

### Finding the target of a step

A target names an element in one of two ways: by its role and its accessible name, or by a `data-studeo-*` attribute that the element carries.

- The accessible name is compared as a whole, letter for letter: the name `Save` finds a control named `Save`, not one named `Save draft` and not one named `save`.
- A target must match exactly one element of the page. A step whose target matches no element, or more than one, fails. `expectHidden` is the one step that accepts no match at all.

### What a step means

A step starts when the one before it has finished and waits for nothing else.

- `click`: the target is activated the way a user activates it with a pointer.
- `focus`: the target is given the keyboard focus.
- `fill`: the value of the target is replaced by the given text.
- `press`: the key is pressed on the element that has the focus. Keys are named as in the scenarios, for example `Space` and `Escape`.
- `expectVisible`: the target is shown to the user: it takes up room on the page and is not hidden.
- `expectHidden`: no element matches the target, or none of the elements that match is shown to the user.
- `expectFocus`: the target has the keyboard focus.
- `expectText`: the text of the target, with each run of white space read as one space, contains the given text. It need not equal it.
- `expectAttribute`: the target carries the attribute. When a `value` is given, the attribute equals it; without a value, the attribute is present, whatever its value, even an empty one.
- `expectNoAttribute`: the target does not carry the attribute, whatever its value would be.

## 7. What the tests refuse

- A data file that does not fit its schema.
- A name that is declared twice, or a reference that does not resolve: a composed component, a token, a fixture, a state or an icon.
- An attribute that does not follow the naming of roots, parts and states.
- A pair of colours below its minimum contrast, in either mode.
- A name of a framework, a template language or a test tool, and a path on somebody's machine.
- A component of the set that is missing, a component with no scenario, and an ARIA attribute or state that no scenario of its component checks.
- A document that is not true to the data: a missing heading, a missing row, an action without its meaning, an unfinished placeholder or a dead link.

## 8. Changing the specification

Adding a component, a prop, a state, an icon or a token does not break an implementation. Renaming or removing one, or changing what it means, does. Every such change is recorded in [CHANGELOG.md](CHANGELOG.md). A change to the data comes with the scenarios that exercise it.
