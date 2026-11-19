# Dark mode

## How it is selected

Dark mode is selected by a class on the root element of the page. The class is `dark`.

- With the class on the root element, every input, every constant and every knob takes its `dark` value.
- Without it, they take their `light` value.
- A derived colour has no value for either mode: it is computed from the inputs and the knobs, so it follows the mode.
- Nothing else changes between the modes. The tokens of the other categories have one value.

Switching the class changes every component at once, because the components read variables and never colours.

## What is not automatic

The package does not read the preference of the system and does not remember a choice. The host decides which mode a page is in, and sets or removes the class.

A host that wants to follow the system preference reads it, sets the class before the page is first drawn so that the wrong mode never flashes, and updates the class when the preference changes:

```js
const query = window.matchMedia('(prefers-color-scheme: dark)');
const apply = () => document.documentElement.classList.toggle('dark', query.matches);

apply();
query.addEventListener('change', apply);
```

The script runs inline in the head of the page, ahead of the stylesheets that draw the first frame.

## What a component may do

A component never asks which mode is active and has no rule of its own for one of them. If a component needs a different look in the dark mode, the look belongs in a colour token, and the token gets its two values.

## What a UI package proves

Each UI package has a test with two parts. With the class on the root element, every input, constant and knob variable equals the `dark` value in `tokens.json` and every derived colour equals what the engine computes from them; without the class, they equal the `light` values and what the engine computes from them.

## Readability in both modes

The pairs of colours listed in `tokens.json` reach their lowest contrast ratio in the light mode and in the dark mode, proved by the browser tests of each UI package on the colours that the engines compute. A host that sets an input for one mode checks the pairs of that mode.
