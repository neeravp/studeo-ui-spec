# ui-spec

A stack-neutral specification of the Studeo user interface: design tokens, a catalogue of components with their contract (props, slots, attributes, accessibility) and browser scenarios that say how each component behaves. It is made of documents and JSON files. It is for the authors of UI packages, who implement the components in their own stack and test them against the same fixtures and scenarios. It ships no component, no stylesheet and no script.

## Status

For the maintainer's own use, offered as-is under the MIT license. The specification is a draft: any part of it may change until the first release. Issues and pull requests may go unanswered; forks are welcome. The principles and reasons are in [DESIGN.md](DESIGN.md) and the contract is in [SPEC.md](SPEC.md).

## Table of Contents

<!-- generated:contents -->
- [Status](#status)
- [Installation](#installation)
- [Usage](#usage)
- [Documents](#documents)
- [License](#license)
<!-- /generated:contents -->

## Installation

Add the repository to the `composer.json` of the package that implements the specification. While the package is only local, a `path` entry does it; once it is pushed, a `vcs` entry does:

```json
{
    "repositories": [
        { "type": "vcs", "url": "<the URL of the repository>" }
    ]
}
```

Then require it as a development dependency. A UI package reads the files and tests against them; it does not need them at run time.

```bash
composer require --dev studeo/ui-spec:@dev
```

The requirement `@dev` stays until the first release is tagged. After that, a version constraint takes its place.

## Usage

`spec.json` names the data files and the schemas that describe them. A package finds everything from there:

```php
$root = 'vendor/studeo/ui-spec';
$spec = json_decode(file_get_contents("{$root}/spec.json"), true);
$components = json_decode(file_get_contents($root.'/'.$spec['files']['components']['data']), true)['components'];

echo $components[0]['name'];
```

This prints `button`. A UI package then does four things with what it reads:

- It implements each component with the props, slots, attributes and accessibility that the contract of the component lists.
- It provides every token as the CSS variable that `tokens.json` names, and a dark mode through a class on the root element.
- It provides a gallery page for every fixture, written `<component>.<case>`, and runs the scenarios against those pages in a browser.
- When something needs to change, it changes the specification first. It does not depart from it silently.

## Documents

- [DESIGN.md](DESIGN.md): the problem, the principles and the reasons for the choices.
- [SPEC.md](SPEC.md): the files, the vocabularies and the format of the scenarios.
- [docs/principles.md](docs/principles.md): the principles that components are built on.
- [docs/patterns.md](docs/patterns.md): the arrangements that components have in common.
- [docs/customization.md](docs/customization.md): how an author changes a component.
- [docs/theming.md](docs/theming.md): the tokens, the pairs of colours and how a host changes them.
- [docs/dark-mode.md](docs/dark-mode.md): how the dark mode works.
- [docs/components.md](docs/components.md): the contract of every component.

## License

MIT.
