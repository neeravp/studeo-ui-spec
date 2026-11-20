# Theming

A theme is a set of values for the design tokens of the specification. For colour, rounding and typeface, a component reads tokens and nothing else. A host changes how every component looks by changing token values, never by editing a component. For colour a host sets only seven inputs; every other colour is derived from them.

The tokens are what a host brands, and only that: the colours, the rounding of the corners and the typeface. Lengths, spacing, the size and weight of text and shadows are not tokens. They are fixed design choices that no host changes, and an implementation writes them as the utility classes of its own stylesheet. A change to a layout is a change of a class, never a new token. The sizes that a prop stands for (the height of a control, the width of a dialog, the size of an icon or of text) are written in the specification as the measures of the prop, in rem, so that every implementation draws the same size.

## The tokens

`tokens.json` lists every token with its name, its category, the name of its CSS variable and its reference value. The categories are `color`, `radius` and `font`; the only font tokens are the two typefaces.

- The name of a token starts with its category: `color-accent`, `radius-control`, `font-family-sans`.
- The CSS variable is the name with the prefix `--studeo-`: `--studeo-color-accent`.
- A colour token has a `kind`. An **input** is a colour that a host sets, and has two values, `light` and `dark`. A **derived** colour has no value of its own: it has a `derive` rule, a CSS colour that reads the inputs, other derived colours and the knobs. A **constant** has a `light` and a `dark` value that no host is asked to set and no rule makes. Every other token has one `value` that holds in both modes.
- The seven inputs are `color-background`, `color-text` and the five roles `color-accent`, `color-danger`, `color-success`, `color-warning` and `color-info`. A role is a family: its colour, the colour of text on it (`-foreground`), its pale background (`-subtle`) and its colour for text on the page (`-text`).
- The reference colours of the inputs are written in hex so that the specification can check them. A theme of a host is not limited to hex (see below).
- The **knobs** are the fixed numbers that the rules read (how much lighter the surface is than the page, how much of the text colour a border takes, and so on), each with a value for the light mode and one for the dark mode. They belong to the design: a host does not set them.

The tokens name roles, not components. There is a `color-border-strong` for the border of any control; there is no token for the border of an input.

## The token list

<!-- generated:tokens -->
### color: the inputs

| Token | Variable | Light | Dark | Meaning |
| --- | --- | --- | --- | --- |
| `color-background` | `--studeo-color-background` | `#fafafa` | `#09090b` | The background of the page. |
| `color-text` | `--studeo-color-text` | `#18181b` | `#fafafa` | The colour of body text and headings. |
| `color-accent` | `--studeo-color-accent` | `#7c3aed` | `#a78bfa` | The colour of the main action and of a selected control. |
| `color-danger` | `--studeo-color-danger` | `#dc2626` | `#f87171` | The colour of an action that destroys or cannot be undone. |
| `color-success` | `--studeo-color-success` | `#15803d` | `#4ade80` | The colour that confirms that something worked. |
| `color-warning` | `--studeo-color-warning` | `#b45309` | `#fbbf24` | The colour that asks for care without reporting an error. |
| `color-info` | `--studeo-color-info` | `#0369a1` | `#38bdf8` | The colour of neutral information that needs attention. |

### color: derived

| Token | Variable | Rule | Meaning |
| --- | --- | --- | --- |
| `color-surface` | `--studeo-color-surface` | `color-mix(in oklab, var(--studeo-color-background), white var(--studeo-derive-surface-lift))` | The background of a card, a table, a panel or a dialog, which sits on the page. |
| `color-border` | `--studeo-color-border` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-text) var(--studeo-derive-border-mix))` | The line between regions and around a card; decorative. |
| `color-border-strong` | `--studeo-color-border-strong` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-text) var(--studeo-derive-border-strong-mix))` | The border of a control such as an input, a checkbox or a switch; it must be seen against the page, the surface and the fill of a field. |
| `color-field` | `--studeo-color-field` | `color-mix(in oklab, var(--studeo-color-surface), var(--studeo-color-text) 4%)` | The fill of a text control, one step above the surface, so that the control is found without relying on its border alone. |
| `color-text-muted` | `--studeo-color-text-muted` | `color-mix(in oklab, var(--studeo-color-text), var(--studeo-color-background) var(--studeo-derive-text-muted-mix))` | The colour of secondary text such as hints and captions. |
| `color-accent-foreground` | `--studeo-color-accent-foreground` | `contrast-color(var(--studeo-color-accent))` | The colour of text and icons on the accent colour. |
| `color-accent-subtle` | `--studeo-color-accent-subtle` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-accent) var(--studeo-derive-subtle-mix))` | A pale tint of the accent colour for a selected row or a badge. |
| `color-accent-text` | `--studeo-color-accent-text` | `oklch(from var(--studeo-color-accent) clamp(var(--studeo-derive-text-min), l, var(--studeo-derive-text-max)) c h)` | The accent colour for text on the page or on a surface, such as a link. |
| `color-danger-foreground` | `--studeo-color-danger-foreground` | `contrast-color(var(--studeo-color-danger))` | The colour of text and icons on the danger colour. |
| `color-danger-subtle` | `--studeo-color-danger-subtle` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-danger) var(--studeo-derive-subtle-mix))` | The background of an alert that reports an error. |
| `color-danger-text` | `--studeo-color-danger-text` | `oklch(from var(--studeo-color-danger) clamp(var(--studeo-derive-text-min), l, var(--studeo-derive-text-max)) c h)` | The colour of the title of an error alert and of an error message. |
| `color-success-foreground` | `--studeo-color-success-foreground` | `contrast-color(var(--studeo-color-success))` | The colour of text and icons on the success colour. |
| `color-success-subtle` | `--studeo-color-success-subtle` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-success) var(--studeo-derive-subtle-mix))` | The background of an alert that reports success. |
| `color-success-text` | `--studeo-color-success-text` | `oklch(from var(--studeo-color-success) clamp(var(--studeo-derive-text-min), l, var(--studeo-derive-text-max)) c h)` | The colour of the title of a success alert. |
| `color-warning-foreground` | `--studeo-color-warning-foreground` | `contrast-color(var(--studeo-color-warning))` | The colour of text and icons on the warning colour. |
| `color-warning-subtle` | `--studeo-color-warning-subtle` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-warning) var(--studeo-derive-subtle-mix))` | The background of an alert that warns. |
| `color-warning-text` | `--studeo-color-warning-text` | `oklch(from var(--studeo-color-warning) clamp(var(--studeo-derive-text-min), l, var(--studeo-derive-text-max)) c h)` | The colour of the title of a warning alert. |
| `color-info-foreground` | `--studeo-color-info-foreground` | `contrast-color(var(--studeo-color-info))` | The colour of text and icons on the info colour. |
| `color-info-subtle` | `--studeo-color-info-subtle` | `color-mix(in oklab, var(--studeo-color-background), var(--studeo-color-info) var(--studeo-derive-subtle-mix))` | The background of an alert that informs. |
| `color-info-text` | `--studeo-color-info-text` | `oklch(from var(--studeo-color-info) clamp(var(--studeo-derive-text-min), l, var(--studeo-derive-text-max)) c h)` | The colour of the title of an information alert. |
| `color-focus-ring` | `--studeo-color-focus-ring` | `var(--studeo-color-accent-text)` | The colour of the outline of the focused control. |

### color: constants

| Token | Variable | Light | Dark | Meaning |
| --- | --- | --- | --- | --- |
| `color-overlay` | `--studeo-color-overlay` | `#00000080` | `#000000b3` | The veil behind a dialog; it has an alpha channel and takes part in no contrast pair. |

### knobs

| Knob | Variable | Light | Dark | Meaning |
| --- | --- | --- | --- | --- |
| `derive-surface-lift` | `--studeo-derive-surface-lift` | `100%` | `8%` | The share of white that is mixed into the page colour to make the surface, which is lighter than the page in both modes. |
| `derive-border-mix` | `--studeo-derive-border-mix` | `10%` | `14%` | The share of the text colour that is mixed into the page colour to make the border of a region. |
| `derive-border-strong-mix` | `--studeo-derive-border-strong-mix` | `56%` | `58%` | The share of the text colour that is mixed into the page colour to make the border of a control. |
| `derive-text-muted-mix` | `--studeo-derive-text-muted-mix` | `32%` | `24%` | The share of the page colour that is mixed into the text colour to make secondary text. |
| `derive-subtle-mix` | `--studeo-derive-subtle-mix` | `6%` | `18%` | The share of a role colour that is mixed into the page colour to make its pale background. |
| `derive-text-min` | `--studeo-derive-text-min` | `0` | `0.82` | The lowest lightness that the text colour of a role may have (0 to 1). |
| `derive-text-max` | `--studeo-derive-text-max` | `0.44` | `1` | The highest lightness that the text colour of a role may have (0 to 1). |

### radius

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `radius-control` | `--studeo-radius-control` | `0.375rem` | The rounding of the corners of a control. |
| `radius-container` | `--studeo-radius-container` | `0.75rem` | The rounding of the corners of a card, a panel or a dialog. |
| `radius-pill` | `--studeo-radius-pill` | `9999px` | The rounding of a badge or a switch. |

### font

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `font-family-sans` | `--studeo-font-family-sans` | `ui-sans-serif, system-ui, sans-serif` | The typeface of the interface. |
| `font-family-mono` | `--studeo-font-family-mono` | `ui-monospace, monospace` | The typeface of codes such as a recovery code. |
<!-- /generated:tokens -->

## What an implementation does

An implementation defines every input, every constant, every knob and every other variable on the root element with the value of the light mode, and defines the inputs, the constants and the knobs again for the dark mode as `dark-mode.md` describes. It writes the rule of each derived colour as the value of its variable, exactly as `tokens.json` gives it, and declares the derived variables on the root element and on every element that carries the scope attribute (below).

A derived variable that reads another variable is computed on the element where it is declared. That is why the scope attribute exists: a region that sets inputs of its own must have its derived colours computed again from them.

A component refers to a variable and never repeats its value. No literal colour or typeface appears in a component, and no hard-coded colour of a palette. A length is not a token and is written as a utility class.

States such as hover and pressed have no tokens. An implementation derives them from the token of the component, for example by mixing the colour with the colour of the text on it.

## What a host does

A host brands by setting the inputs, for the light mode and for the dark mode, and nothing else: the derived colours follow. It sets them on the root element to theme the whole page. To theme one region it sets them on a container that carries the attribute named in the `scope` of `tokens.json`, `data-studeo-scope`; without the attribute the derived colours inside the container stay computed from the inputs of the page.

A host may still set the variable of a derived colour itself, for example one border colour; its own value wins over the rule, and the colours that read that variable follow it.

A value can be anything that CSS accepts for that kind of value: a colour in any notation, a length in any unit for the rounding, a list of typefaces. The reference values do not limit a host.

## Keeping text readable

`tokens.json` also lists the pairs of colours that are used together, such as the text on a surface or the text on the accent colour, with the lowest contrast ratio each pair must reach. The rules of the derived colours are made to reach every pair for any sensible inputs, but a rule is CSS and only an engine can compute it, so the pairs are proved by the browser tests of each UI package, which read the colours that the engines compute, in both modes. The tests of the specification check that the rules are well formed (the seven inputs, rules that read only tokens and knobs, no literal colour, no cycle, every knob read) and that every pair names colours that take part in a contrast.

A host that sets an input changes the contrast of every pair that uses it, and the list says which pairs those are. Checking them is the host's job, and the gallery of a UI package shows them for the colours in force. The foreground of a role is chosen by the engine with `contrast-color()`: black or white, in nearly every case the one with the higher contrast. Very near the middle (a role colour whose relative luminance is close to 0.18) an engine may pick the other, and the pair then falls short, although the better of white and black always reaches 4.58 to one; a small change of the role colour settles it. A page colour that is strongly tinted or of middle lightness leaves too little room for the text colours; the remedy is another input.

The pairs are:

<!-- generated:contrast -->
| Text | On | Minimum | Why |
| --- | --- | --- | --- |
| `color-text` | `color-background` | 4.5 | text on background is read as text |
| `color-text` | `color-surface` | 4.5 | text on surface is read as text |
| `color-text-muted` | `color-background` | 4.5 | text muted on background is read as text |
| `color-text-muted` | `color-surface` | 4.5 | text muted on surface is read as text |
| `color-accent-foreground` | `color-accent` | 4.5 | accent foreground on accent is read as text |
| `color-accent-text` | `color-background` | 4.5 | accent text on background is read as text |
| `color-accent-text` | `color-surface` | 4.5 | accent text on surface is read as text |
| `color-text` | `color-accent-subtle` | 4.5 | text on accent subtle is read as text |
| `color-accent-text` | `color-accent-subtle` | 4.5 | accent text on accent subtle is read as text |
| `color-border-strong` | `color-background` | 3 | border strong on background is seen as the outline of a control |
| `color-border-strong` | `color-surface` | 3 | border strong on surface is seen as the outline of a control |
| `color-focus-ring` | `color-background` | 3 | focus ring on background is seen as the outline of a control |
| `color-focus-ring` | `color-surface` | 3 | focus ring on surface is seen as the outline of a control |
| `color-text` | `color-field` | 4.5 | text on field is read as text |
| `color-text-muted` | `color-field` | 4.5 | text muted on field is read as text |
| `color-danger-text` | `color-field` | 4.5 | danger text on field is read as text |
| `color-border-strong` | `color-field` | 3 | border strong on field is seen as the outline of a control |
| `color-focus-ring` | `color-field` | 3 | focus ring on field is seen as the outline of a control |
| `color-danger-foreground` | `color-danger` | 4.5 | danger foreground on danger is read as text |
| `color-danger-text` | `color-danger-subtle` | 4.5 | danger text on danger subtle is read as text |
| `color-danger-text` | `color-surface` | 4.5 | danger text on surface is read as text |
| `color-text` | `color-danger-subtle` | 4.5 | text on danger subtle is read as text |
| `color-success-foreground` | `color-success` | 4.5 | success foreground on success is read as text |
| `color-success-text` | `color-success-subtle` | 4.5 | success text on success subtle is read as text |
| `color-success-text` | `color-surface` | 4.5 | success text on surface is read as text |
| `color-text` | `color-success-subtle` | 4.5 | text on success subtle is read as text |
| `color-warning-foreground` | `color-warning` | 4.5 | warning foreground on warning is read as text |
| `color-warning-text` | `color-warning-subtle` | 4.5 | warning text on warning subtle is read as text |
| `color-warning-text` | `color-surface` | 4.5 | warning text on surface is read as text |
| `color-text` | `color-warning-subtle` | 4.5 | text on warning subtle is read as text |
| `color-info-foreground` | `color-info` | 4.5 | info foreground on info is read as text |
| `color-info-text` | `color-info-subtle` | 4.5 | info text on info subtle is read as text |
| `color-info-text` | `color-surface` | 4.5 | info text on surface is read as text |
| `color-text` | `color-info-subtle` | 4.5 | text on info subtle is read as text |
<!-- /generated:contrast -->

## Changing the brand colour

The accent colour is one input, `color-accent`. Its foreground, its pale background, its text colour and the focus ring are derived from it. To rebrand, set `color-accent` for the light mode and for the dark mode, and check the pairs it belongs to. The foreground is black or white, whichever reads better on the new colour.

## What is not a token

- Outer spacing. A component carries no margin around itself; the page that places the component spaces it.
- Anything that depends on the size of the screen. The specification does not define breakpoints.
