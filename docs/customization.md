# Customization

A component can be changed in four ways. They are given here in the order in which to try them: stop at the first one that does what is needed.

## Change the tokens

Change a token and every component that uses it changes with it. For colour, set the inputs and the derived colours follow. This is the way to a brand colour, a typeface, a different rounding or a dark mode. `theming.md` and `dark-mode.md` describe it.

## Add classes and attributes

An author may write classes and attributes on a component, and each goes where it is useful.

- **Classes** land on the outermost element of the component and are merged with its own: both lists stay on the element, the author's after the component's, and neither replaces the other.
- **Classes for the control.** A component that wraps a control, such as an input, takes the prop `control-class`: its classes land on the control and are merged with the control's own in the same way. The name is the same on every such component. A component that has no control has no such prop.
- **Every other attribute** is delivered to the element that does the work. In a component made of one element, that is the outermost element. In a component that wraps a control, such as a field around an input, it is the control, so that attributes about behaviour (a name, a placeholder, a binding, autofocus) reach the control while classes, which are about the look, reach the wrapper. The contract of every component names that element in `attributesTarget`. An attribute written by the author replaces a default of the component for the same attribute, except the ones that the contract sets: the role, the ARIA states and the `data-studeo-*` attributes. The component keeps those.
- A prop is not an attribute: a prop is declared in the contract of the component and sets it up.

When a class written by the author and a class of the component set the same property, the author's class wins, whatever its specificity and wherever it stands in the file. An implementation guarantees this with a cascade layer: it puts all the styles of its components in a layer named `studeo`, and the layer is declared before every layer of the author's, so that any rule of the author outranks it. The author never needs a marker of importance to win.

A page that has layers of its own states their order once, with `studeo` first:

```css
@layer studeo, base, page;
```

## Select by the `data-studeo-*` attributes

A component sets attributes that a stylesheet can select, and one rule written against them changes every matching element at once.

- `data-studeo-<component>` is on the outermost element of the component: `data-studeo-button`.
- `data-studeo-<component>-<part>` is on one part of the component.
- `data-studeo-<state>` is present for as long as the component is in that state: `data-studeo-loading`, `data-studeo-disabled`. A state name carries no component name, and a given state has the same name in every component.

```css
[data-studeo-button][data-studeo-loading] {
    cursor: progress;
}
```

These attributes are public. They are declared in `components.json`, a test keeps those declarations and these documents in step, and renaming or removing one follows the change policy of the specification. A stylesheet selects by these attributes and never by a class name, an element id or the position of an element, because only these are promised.

## Swap in your own markup

An implementation lets an author replace the markup of a component with the author's own. The replacement keeps the contract of the component: the same role, the same accessible name, the same keys, the same ARIA states and the same `data-studeo-*` attributes. A replacement that drops any of them has left the specification, and the scenarios of the specification no longer describe it.

A page that has replaced a component stops receiving the changes that the implementation makes to it. Use this way last.

## What stays fixed

The accessibility contract of a component and the names of its `data-studeo-*` attributes cannot be customized. The scenarios and the stylesheets of authors depend on them.
