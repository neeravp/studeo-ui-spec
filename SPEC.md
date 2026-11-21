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
| [`pagination`](docs/components.md#pagination) | The way through the pages of a list that the server pages: a line that says where the user is, a choice of the page size and the controls to the first, the previous, the next and the last page. | optional |
| [`textarea`](docs/components.md#textarea) | A control for text of several lines. | optional |
| [`avatar`](docs/components.md#avatar) | The picture of a person, or the initials of the name when there is no picture or it does not load. | none |
| [`badge`](docs/components.md#badge) | A small label that says what state or kind something is in, such as the status of an account. | none |
| [`menu`](docs/components.md#menu) | A button that opens a short list of actions and places to go. It closes again when the user picks one, presses Escape or goes elsewhere. | optional |
| [`menu-item`](docs/components.md#menu-item) | One choice of a menu: a link to another place, or a button that does something. | none |
| [`menu-separator`](docs/components.md#menu-separator) | A line between two groups of items of a menu. | none |
| [`file-input`](docs/components.md#file-input) | A control that takes files from the user: a place to click or to drop files on, the list of what was chosen, and the refusal of a file that is not allowed, before anything is sent. | optional |
| [`image-crop`](docs/components.md#image-crop) | A control that takes a picture from the user, lets the user choose the part of it to keep by moving and scaling it behind a frame of the shape that is asked for, and gives the host that part as a file of the size that is asked for. | required |
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
| `data-studeo-dragging` | Present while files are being dragged over the component. |
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
| `plus` | A cross of two bars, for adding and for making larger. |
| `search` | A magnifying glass, for searching. |
| `upload` | An arrow that points up from a tray, for sending files. |
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
- `setFiles` (target, files)
- `dragFiles` (target, files)
- `dropFiles` (target, files)
- `dragBy` (target, dx, dy)
- `expectFiles` (target, files)
- `expectImage` (target, type, width, height)
<!-- /generated:actions -->

A target is found by role and accessible name, or by a `data-studeo-*` attribute. A step that checks an attribute names an ARIA attribute or a state that the component of the scenario declares, or `disabled` where the component has a prop of that name. Waiting is not an action: a step waits by the rule below, and any other time limit is stated in the contract of the component.

### Finding the target of a step

A target names an element in one of two ways: by its role and its accessible name, or by a `data-studeo-*` attribute that the element carries.

- The accessible name is compared as a whole, letter for letter: the name `Save` finds a control named `Save`, not one named `Save draft` and not one named `save`.
- A target must match exactly one element of the page. A step whose target matches no element, or more than one, fails. `expectHidden` is the one step that accepts no match at all.

### What a step means

A step starts when the one before it has finished, and ends when the page is quiet. The page is quiet when no element carries `aria-busy="true"`, except an element that is in the state `data-studeo-loading` or inside an element that is (a component puts `aria-busy` on its inner control while its root carries the state). A component that does work of its own, which it ends by itself (the image crop while it reads a picture and while it makes a file), sets `aria-busy="true"` on its root while it works and `aria-busy="false"` when it is idle. The loading state belongs to the host, which ends it, so a step does not wait for what is in it. A runner waits a bounded time for the page to be quiet; a page that is still busy when the time is over fails the step. A step waits for nothing else.

- `click`: the target is activated the way a user activates it with a pointer.
- `focus`: the target is given the keyboard focus.
- `fill`: the value of the target is replaced by the given text.
- `press`: the key is pressed on the element that has the focus. Keys are named as in the scenarios, for example `Space` and `Escape`; a key held with another is written with a plus, as `Shift+ArrowLeft`.
- `expectVisible`: the target is shown to the user: it takes up room on the page and is not hidden.
- `expectHidden`: no element matches the target, or none of the elements that match is shown to the user.
- `expectFocus`: the target has the keyboard focus.
- `expectText`: the text of the target, with each run of white space read as one space, contains the given text. It need not equal it.
- `expectAttribute`: the target carries the attribute. When a `value` is given, the attribute equals it; without a value, the attribute is present, whatever its value, even an empty one.
- `expectNoAttribute`: the target does not carry the attribute, whatever its value would be.
- `setFiles`: the files are given to the file input the way a user gives them in the dialog that chooses files: the input holds exactly these files and the events of a choice follow. The target is a file input, or an element that holds exactly one. The files are samples (see below).
- `dragFiles`: files are dragged over the target and stay there, as a pointer that has not been released does; the target receives the events of entering and moving over it.
- `dropFiles`: the files are dropped on the target, after being dragged over it if they were not yet; the target receives the event of the drop.
- `dragBy`: a pointer is pressed in the middle of the target, moved by `dx` pixels to the right and `dy` pixels down (negative to the left and up), and released. The move goes through the points in between.
- `expectFiles`: the file input that is the target, or that the target holds, holds exactly the files with these names, in this order. An empty list means that it holds none.
- `expectImage`: the first file that the file input holds is an image of the given type, width and height in pixels, and, at each point that is given, it has the named colour. A point is given as whole percentages of the width and of the height from the top left corner, and it is the pixel that contains the point. A colour is the named one when each of its channels is within 48 of the colour's own, which is red (255, 0, 0), green (0, 255, 0), blue (0, 0, 255), yellow (255, 255, 0) or white (255, 255, 255), and `transparent` is a pixel whose alpha is below 16.

### The sample files

A step that gives files to a control names them from this closed list. The runner makes each one, so that the specification carries no file. An image is drawn in four equal quadrants: red at the top left, green at the top right, blue at the bottom left and yellow at the bottom right.

| Sample | What it is |
| --- | --- |
| `quadrants-landscape.png` | A png of 400 by 300 pixels, in four quadrants. |
| `quadrants-portrait.png` | A png of 300 by 400 pixels, in four quadrants. |
| `small.png` | A png of 100 by 100 pixels, in four quadrants. |
| `rotated.jpg` | A jpeg stored as 200 by 100 pixels, red in its left half and blue in its right half, with the orientation mark 6 of the camera, so that it is shown turned a quarter clockwise: 100 by 200 pixels, red above and blue below. |
| `transparent.png` | A png of 300 by 300 pixels with no opaque pixel. |
| `large.png` | A png of 8000 by 6000 pixels, in four quadrants. |
| `heavy.png` | A png of 100 by 100 pixels, in four quadrants, followed by padding up to exactly 2097152 bytes. |
| `notes.txt` | A text file with the words Notes, of the type text/plain. |

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
