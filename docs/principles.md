# Principles

These principles explain why the specification looks the way it does. A UI package that implements the specification follows them, and where the specification says nothing they decide. They were shaped by reading the principles of Flux UI, the library that this one takes as its model, and are written here in terms that hold for any stack.

Each principle says what it asks, why, and how it is checked. "Held by review" means that no test enforces it yet.

## Short in the common case

Using a component with no options gives the usual result, written in the fewest words. The label of an input is one option of the input; to get a labelled input the author does not assemble a field, a label and an error by hand.

*Why.* Most of a page is the usual case, and short markup is easy to read and to review.

*Checked.* The props `variant` and `size` must be enums with a default, and the default of an enum is one of its values (`ReferenceTest`).

## Open to detail when needed

A component that has parts names them. An author who needs control over one part builds the parts individually and keeps the component. Built part by part, a component has the same structure as in its short form.

*Why.* A component with only a short form has to be given up the first time it falls short. One with only a long form makes the usual case laborious. Both forms are needed.

*Checked.* Parts and slots are declared in `components.json` (`SchemaTest`). That the short form and the long form agree is checked by each UI package.

## Everyday names, kept short and repeated

A name is the word people already use for what they see: tabs, dialog, switch. It is as short as it can be while staying clear: a single word where that is enough, and otherwise words joined by hyphens with the component first. A word keeps its meaning wherever it appears. `variant` and `size` always choose the look, `disabled` always switches a control off, `loading` always marks an action that is pending.

*Why.* A name that is brief and keeps coming back is learned once.

*Checked.* Every name is lower case words joined by hyphens and is unique within its list (`SchemaTest`, `ReferenceTest`). That a word keeps its meaning is held by review.

## Large from small

The core components are small, and larger ones are assembled from them. The contract of a larger component lists the components it is built from.

*Why.* A big component with a fixed shape resists change. Small ones can be recombined in ways that nobody planned for.

*Checked.* Every component named in a list of `composes` exists (`ReferenceTest`).

## Lean on the platform

Where the browser already provides what a component needs, the component uses it: the proper element for a button, an input, a dialog or a disclosure, and the browser's own handling of focus, keys and form data. A component does not rebuild any of it.

*Why.* Platform features behave alike in every browser, look after focus and keys, and are understood by assistive technology with no extra work.

*Checked.* Held by review. The scenarios find elements by role and accessible name, so a component built from the wrong kind of element fails them.

## Style first, script last

What markup and style can do, they do. Script is for what they cannot do, and a component that needs any says so in its contract.

*Why.* Less to download, less to break, and a page that already works before its first script has run.

*Checked.* Held by review, until the contract of a component says whether it needs script.

## Accessible without asking

A component is accessible in its default form, with no effort from the author. Its contract gives its role, the source of its accessible name, the keys that operate it and the ARIA states it sets. A control shows a visible focus, and motion gives way to the preference for less of it. If a component cannot make up an accessible name that it needs, it requires one.

*Why.* When accessibility depends on the author's diligence, most pages go without it.

*Checked.* Every component has the contract (`SchemaTest`). The scenarios use roles and accessible names. Every pair of colours that is used together reaches its contrast ratio in both modes (`TokenTest`). The accessibility checks of each UI package prove the rest.

## Styling is ours, spacing is yours

A component styles itself and puts no margin around itself. The page that places a component decides how much room it gets.

*Why.* How much room a component needs is a matter of where it sits. Margins built into components are overridden again and again, and when they are left in place they make pages look uneven.

*Checked.* Held by review. Each UI package tests that its components have no outer margin.

## Values come from tokens

A component takes every colour, corner radius and typeface from a token and never carries a value of its own. Lengths, spacing and shadows are the implementation's utility classes, and the sizes that a prop stands for are its measures.

*Why.* One place changes the look of everything, and dark mode costs the components nothing.

*Checked.* The tokens and their variables follow the rules, and a token is a colour, a radius or a font family, never a length (`TokenTest`). Every size of a component has its measure, and the measures grow with the size (`ReferenceTest`, `ComponentSetTest`). That a component holds no literal value is held by review and tested by each UI package.

## Variants, not copies

When a component needs another look, it gets a new value of `variant` or a new `data-studeo-*` attribute instead of a copy. The names of the attributes are public, and an author may rely on them.

*Why.* A copy no longer receives fixes. A named variant stays part of the specification.

*Checked.* The attribute names follow one convention and are declared (`ReferenceTest`, `AttributeTest`). Changing a public name follows the change policy of the specification.

## Independent of the stack

The specification describes what can be seen from a browser: elements, roles, names, attributes, variables and behaviour. It names no framework, template language or test tool, so that packages for entirely different stacks can implement it.

*Why.* One specification has to serve packages that share nothing except the browser.

*Checked.* The files of the specification name no framework, tool or path on a machine (`NeutralityTest`).
