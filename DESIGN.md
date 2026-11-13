# Design

Why ui-spec is shaped as it is. The contract is in [SPEC.md](SPEC.md).

## The problem

Several applications and packages need the same screens: signing in, changing settings, working through a list. Each frontend stack draws them its own way. Without a shared definition the stacks drift. A component gets another name in one stack, another state in the next, another keyboard behaviour in the third, and a page that is right in one is wrong in another. Tests written for one stack cannot be used for the next, so every stack is tested on its own terms and nobody can say that two of them agree.

## Principles

The principles that components are built on are in [docs/principles.md](docs/principles.md). These are the principles of the specification itself.

1. **The data files are the source of truth; the documents are derived.** A catalogue written twice disagrees with itself. The reference pages are generated from the data files, and a test reads the documents against the data.
2. **Nothing in the specification is tied to a stack.** A name of a framework, a template language or a test tool would make the specification useful to one stack only. A test refuses such names in the data and in the documents.
3. **A component is described before any code exists.** Its props, slots, attributes, accessibility, script need, tokens and fixtures are written once, so that every implementation answers the same questions.
4. **Every claim the specification makes about itself is checked.** A rule that nothing enforces is a hope. The format, the references, the colour contrast, the coverage of the scenarios and the truth of the documents are each checked by a test.
5. **The vocabularies are small and closed.** The states, the icons and the actions of a scenario are lists. A new word costs every implementation something, so a list grows when a component needs it and not before.
6. **Accessibility is part of the contract.** A component states its role, how it is named, its keyboard behaviour and the ARIA attributes it sets, and a scenario must check each of them.
7. **Script is the last resort.** A component says whether it needs script and why. The platform does everything else.

## Choices and the reasons

- **The data is JSON, described by JSON Schema.** The rejected alternatives were a class or an array per component, which ties the specification to one language, and prose alone, which nothing can check.
- **Scenarios are data with a closed list of actions, not test code.** Code for one browser tool would tie the specification to that tool. An implementation turns the actions into the calls of its own tool, and the same scenarios serve every stack.
- **An element is found by its role and accessible name, or by a `data-studeo-*` attribute, never by a class, an identifier or a position.** The first two are what users and assistive technology rely on, and the attribute is what a style may rely on. A class or a position changes for reasons that have nothing to do with behaviour.
- **The classes an author writes are merged with the component's own, and the component's styles sit in a cascade layer that comes before the author's.** Replacing the classes would break the component, and a style that forces itself through with `!important` would leave an author no way to answer. The layer lets the author win without it.
- **Colour tokens name roles and have a light and a dark value; there is no token for each component.** Tokens per component multiply without end. The dark mode is a class on the root element so that a host can switch it, which a media query alone does not allow.
- **A code input is one native control that looks like separate boxes.** One control for each character would break pasting and the filling in by the browser, and it would need script.
- **A data table is driven by the server.** Its props mirror the answer of a list, sorting is a link on the column header and the search is a form, so that the state is in the address and the page works without script. Paging and sorting in the browser would be wrong for a long list.
- **A message that stays is an alert; a message that hides itself is a toast, and only for success and information.** A message that vanishes can be missed by anyone who reads slowly and by users of a screen reader. One component with a timer for every variant would make that mistake easy.
- **A page for one task, such as signing in, is a pattern and not a component.** It is a page, not a part, and the space around its parts belongs to the page.
- **The scenarios cannot wait.** A closed list of actions stays simple. A time limit is stated in the contract and in the patterns, and each implementation tests it by itself.

## Not goals

- An implementation: there is no component code, no stylesheet and no script here.
- Icon artwork: the specification names the icons and leaves the drawing to the implementation.
- Breakpoints or rules for the size of the screen.
- Outer spacing: the page spaces the components.
- Selection of rows and actions on many rows at once in the data table. They can be added later without breaking what exists.
- A tool that runs the scenarios. Each UI package chooses its own.
