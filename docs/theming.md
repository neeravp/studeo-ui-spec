# Theming

A theme is a set of values for the design tokens of the specification. For colour, type, rounding, shadow and the space inside it, a component reads tokens and nothing else. A host changes how every component looks by changing token values, never by editing a component.

## The tokens

`tokens.json` lists every token with its name, its category, the name of its CSS variable and its reference value. The categories are `color`, `space`, `radius`, `size`, `font` and `shadow`.

- The name of a token starts with its category: `color-accent`, `space-4`, `radius-control`.
- The CSS variable is the name with the prefix `--studeo-`: `--studeo-color-accent`.
- A colour token has two values, `light` and `dark`. Every other token has one `value` that holds in both modes.
- The reference colours are written in hex so that the specification can check them. A theme of a host is not limited to hex (see below).

The tokens name roles, not components. There is a `color-border-strong` for the border of any control; there is no token for the border of an input.

## The token list

<!-- generated:tokens -->
### color

| Token | Variable | Light | Dark | Meaning |
| --- | --- | --- | --- | --- |
| `color-background` | `--studeo-color-background` | `#fafafa` | `#09090b` | The background of the page. |
| `color-surface` | `--studeo-color-surface` | `#ffffff` | `#18181b` | The background of a card, a table, a panel or a dialog, which sits on the page. |
| `color-border` | `--studeo-color-border` | `#e4e4e7` | `#27272a` | The line between regions and around a card; decorative. |
| `color-border-strong` | `--studeo-color-border-strong` | `#8a8a93` | `#72727c` | The border of a control such as an input, a checkbox or a switch; it must be seen against the page, the surface and the fill of a field. |
| `color-field` | `--studeo-color-field` | `#f8f8f9` | `#242427` | The fill of a text control, one step above the surface, so that the control is found without relying on its border alone. |
| `color-text` | `--studeo-color-text` | `#18181b` | `#fafafa` | The colour of body text and headings. |
| `color-text-muted` | `--studeo-color-text-muted` | `#52525b` | `#a1a1aa` | The colour of secondary text such as hints and captions. |
| `color-accent` | `--studeo-color-accent` | `#7c3aed` | `#a78bfa` | The colour of the main action and of a selected control. |
| `color-accent-foreground` | `--studeo-color-accent-foreground` | `#ffffff` | `#09090b` | The colour of text and icons on the accent colour. |
| `color-accent-subtle` | `--studeo-color-accent-subtle` | `#f5f3ff` | `#2e1065` | A pale tint of the accent colour for a selected row or a badge. |
| `color-accent-text` | `--studeo-color-accent-text` | `#6d28d9` | `#c4b5fd` | The accent colour for text on the page or on a surface, such as a link. |
| `color-danger` | `--studeo-color-danger` | `#dc2626` | `#f87171` | The colour of an action that destroys or cannot be undone. |
| `color-danger-foreground` | `--studeo-color-danger-foreground` | `#ffffff` | `#09090b` | The colour of text and icons on the danger colour. |
| `color-danger-subtle` | `--studeo-color-danger-subtle` | `#fef2f2` | `#450a0a` | The background of an alert that reports an error. |
| `color-danger-text` | `--studeo-color-danger-text` | `#b91c1c` | `#fca5a5` | The colour of the title of an error alert and of an error message. |
| `color-success` | `--studeo-color-success` | `#15803d` | `#4ade80` | The colour that confirms that something worked. |
| `color-success-foreground` | `--studeo-color-success-foreground` | `#ffffff` | `#052e16` | The colour of text and icons on the success colour. |
| `color-success-subtle` | `--studeo-color-success-subtle` | `#f0fdf4` | `#052e16` | The background of an alert that reports success. |
| `color-success-text` | `--studeo-color-success-text` | `#166534` | `#86efac` | The colour of the title of a success alert. |
| `color-warning` | `--studeo-color-warning` | `#b45309` | `#fbbf24` | The colour that asks for care without reporting an error. |
| `color-warning-foreground` | `--studeo-color-warning-foreground` | `#ffffff` | `#1c1100` | The colour of text and icons on the warning colour. |
| `color-warning-subtle` | `--studeo-color-warning-subtle` | `#fffbeb` | `#451a03` | The background of an alert that warns. |
| `color-warning-text` | `--studeo-color-warning-text` | `#92400e` | `#fcd34d` | The colour of the title of a warning alert. |
| `color-info` | `--studeo-color-info` | `#0369a1` | `#38bdf8` | The colour of neutral information that needs attention. |
| `color-info-foreground` | `--studeo-color-info-foreground` | `#ffffff` | `#082f49` | The colour of text and icons on the info colour. |
| `color-info-subtle` | `--studeo-color-info-subtle` | `#f0f9ff` | `#082f49` | The background of an alert that informs. |
| `color-info-text` | `--studeo-color-info-text` | `#075985` | `#7dd3fc` | The colour of the title of an information alert. |
| `color-focus-ring` | `--studeo-color-focus-ring` | `#6d28d9` | `#c4b5fd` | The colour of the outline of the focused control. |
| `color-overlay` | `--studeo-color-overlay` | `#00000080` | `#000000b3` | The veil behind a dialog; it has an alpha channel and takes part in no contrast pair. |

### space

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `space-1` | `--studeo-space-1` | `0.25rem` | Step 1 of the spacing scale, for the padding and the gaps inside a component. |
| `space-2` | `--studeo-space-2` | `0.5rem` | Step 2 of the spacing scale, for the padding and the gaps inside a component. |
| `space-3` | `--studeo-space-3` | `0.75rem` | Step 3 of the spacing scale, for the padding and the gaps inside a component. |
| `space-4` | `--studeo-space-4` | `1rem` | Step 4 of the spacing scale, for the padding and the gaps inside a component. |
| `space-5` | `--studeo-space-5` | `1.25rem` | Step 5 of the spacing scale, for the padding and the gaps inside a component. |
| `space-6` | `--studeo-space-6` | `1.5rem` | Step 6 of the spacing scale, for the padding and the gaps inside a component. |
| `space-8` | `--studeo-space-8` | `2rem` | Step 8 of the spacing scale, for the padding and the gaps inside a component. |
| `space-10` | `--studeo-space-10` | `2.5rem` | Step 10 of the spacing scale, for the padding and the gaps inside a component. |
| `space-12` | `--studeo-space-12` | `3rem` | Step 12 of the spacing scale, for the padding and the gaps inside a component. |

### radius

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `radius-control` | `--studeo-radius-control` | `0.375rem` | The rounding of the corners of a control. |
| `radius-container` | `--studeo-radius-container` | `0.75rem` | The rounding of the corners of a card, a panel or a dialog. |
| `radius-pill` | `--studeo-radius-pill` | `9999px` | The rounding of a badge or a switch. |

### size

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `size-control-sm` | `--studeo-size-control-sm` | `1.75rem` | The height of a small control. |
| `size-control-md` | `--studeo-size-control-md` | `2.25rem` | The height of a control of the default size. |
| `size-control-lg` | `--studeo-size-control-lg` | `2.75rem` | The height of a large control. |
| `size-container-narrow` | `--studeo-size-container-narrow` | `26rem` | The width of the narrow column of an authentication page. |
| `size-container-medium` | `--studeo-size-container-medium` | `39rem` | The width of the medium dialog: one and a half times the narrow column. |
| `size-container-wide` | `--studeo-size-container-wide` | `52rem` | The width of the wide dialog: twice the narrow column. |
| `size-icon-sm` | `--studeo-size-icon-sm` | `0.875rem` | The size of a small icon. |
| `size-icon-md` | `--studeo-size-icon-md` | `1rem` | The size of an icon of the default size. |
| `size-icon-lg` | `--studeo-size-icon-lg` | `1.25rem` | The size of a large icon. |

### font

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `font-family-sans` | `--studeo-font-family-sans` | `ui-sans-serif, system-ui, sans-serif` | The typeface of the interface. |
| `font-family-mono` | `--studeo-font-family-mono` | `ui-monospace, monospace` | The typeface of codes such as a recovery code. |
| `font-size-xs` | `--studeo-font-size-xs` | `0.75rem` | The font size xs of the type scale. |
| `font-size-sm` | `--studeo-font-size-sm` | `0.875rem` | The font size sm of the type scale. |
| `font-size-base` | `--studeo-font-size-base` | `1rem` | The font size base of the type scale. |
| `font-size-lg` | `--studeo-font-size-lg` | `1.125rem` | The font size lg of the type scale. |
| `font-size-xl` | `--studeo-font-size-xl` | `1.25rem` | The font size xl of the type scale. |
| `font-size-2xl` | `--studeo-font-size-2xl` | `1.5rem` | The font size 2xl of the type scale. |
| `font-weight-normal` | `--studeo-font-weight-normal` | `400` | The normal font weight. |
| `font-weight-medium` | `--studeo-font-weight-medium` | `500` | The medium font weight. |
| `font-weight-semibold` | `--studeo-font-weight-semibold` | `600` | The semibold font weight. |
| `font-leading-tight` | `--studeo-font-leading-tight` | `1.25` | The line height of headings. |
| `font-leading-normal` | `--studeo-font-leading-normal` | `1.5` | The line height of body text. |

### shadow

| Token | Variable | Value | Meaning |
| --- | --- | --- | --- |
| `shadow-sm` | `--studeo-shadow-sm` | `0 1px 2px 0 rgb(0 0 0 / 0.06)` | The shadow of a control that is raised a little. |
| `shadow-md` | `--studeo-shadow-md` | `0 4px 8px -2px rgb(0 0 0 / 0.12)` | The shadow of a card or a menu. |
| `shadow-lg` | `--studeo-shadow-lg` | `0 12px 24px -6px rgb(0 0 0 / 0.2)` | The shadow of a dialog. |
<!-- /generated:tokens -->

## What an implementation does

An implementation defines every variable on the root element with the value of the light mode, and defines the colour variables again for the dark mode as `dark-mode.md` describes.

A component refers to a variable and never repeats its value. No literal colour, length, shadow or typeface appears in a component.

States such as hover and pressed have no tokens. An implementation derives them from the token of the component, for example by mixing the colour with the colour of the text on it.

## What a host does

A host sets its own value for any variable. It does this on the root element to theme the whole page, or on a container to theme one region.

A value can be anything that CSS accepts for that kind of value: a colour in any notation, a length in any unit, a list of typefaces. The reference values do not limit a host.

## Keeping text readable

`tokens.json` also lists the pairs of colours that are used together, such as the text on a surface or the text on the accent colour, with the lowest contrast ratio each pair must reach. The tests of the specification check every pair in both modes.

A host that changes a colour changes the contrast of every pair that uses it, and the list says which pairs those are. Checking them is the host's job.

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

The accent colour is a family of tokens: `color-accent`, `color-accent-foreground`, `color-accent-subtle` and `color-accent-text`, with `color-focus-ring` for the outline of the focused control. To rebrand, set all five, in both modes, and check the pairs they belong to. Setting `color-accent` alone leaves text on the accent colour unreadable when the new colour is lighter or darker than the old one.

## What is not a token

- Outer spacing. A component carries no margin around itself; the page that places the component spaces it.
- Anything that depends on the size of the screen. The specification does not define breakpoints.
