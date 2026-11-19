# Components

The contract of every component, in the order of `components.json`. It is generated from that file, so it is as current as the data. [SPEC.md](../SPEC.md) says what each part of a contract means. The conventions that components share are in [patterns.md](patterns.md), and how an author changes a component is in [customization.md](customization.md).

<!-- generated:reference -->
## button

A control that starts an action.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `primary`, `secondary`, `danger`, `ghost` | `secondary` | The visual weight and meaning of the button. |
| `size` | one of `sm`, `md`, `lg` | `md` | The size of the control. |
| `type` | one of `button`, `submit`, `reset` | `button` | What the button does inside a form. |
| `disabled` | boolean | `false` | The button cannot be activated and is not focusable. |
| `loading` | boolean | `false` | The button shows progress and ignores activation until it ends. While it loads, a spinner takes the place of the icon before the label. |
| `icon` | icon |  | The icon shown before the label. The slot of the same name replaces it. |
| `icon-trailing` | icon |  | The icon shown after the label. The slot of the same name replaces it. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The label of the button. |
| `icon` | no | Any content in place of the icon before the label. |
| `icon-trailing` | no | Any content in place of the icon after the label. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-button` | root | The root element. |
| `data-studeo-button-icon` | part | The element that holds the icon before the label. |
| `data-studeo-button-icon-trailing` | part | The element that holds the icon after the label. |
| `data-studeo-button-spinner` | part | The element that holds the spinner while the button loads. |
| `data-studeo-loading` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `button`. Name from: content.

| Key | Behavior |
| --- | --- |
| `Enter` | Activates the button. |
| `Space` | Activates the button. |

| ARIA attribute | When |
| --- | --- |
| `aria-busy` | The button is loading. |
| `aria-disabled` | The button is loading, so that it stays focusable while it ignores activation. |

**Tokens:** `color-accent`, `color-accent-foreground`, `color-accent-subtle`, `color-danger`, `color-danger-foreground`, `color-surface`, `color-text`, `color-text-muted`, `color-border`, `color-border-strong`, `color-focus-ring`, `radius-control`

**Composes:** `icon`, `spinner`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `button.default` | A button with a label. |
| `button.loading` | A button while its action runs. |
| `button.disabled` | A button that is switched off. |
| `button.with-icon` | A button with an icon before its label. |

## icon

A small picture that carries a meaning or decorates.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | icon | required | The name of the icon. |
| `size` | one of `sm`, `md`, `lg` | `md` | The size of the icon. |
| `label` | string |  | A name for assistive technology. Without it the icon only decorates and is hidden from assistive technology. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-icon` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `img`. Name from: aria-label.

| ARIA attribute | When |
| --- | --- |
| `aria-hidden` | The icon has no label. |
| `aria-label` | The icon has a label. |

**Tokens:** —

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `icon.decorative` | An icon that only decorates. |
| `icon.labelled` | An icon that carries a meaning. |

## spinner

A turning picture that shows that something is loading; it stands still when the user asks for less motion.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | one of `sm`, `md`, `lg` | `md` | The size of the spinner. |
| `label` | string |  | A name for assistive technology. Without it the spinner only decorates and is hidden from assistive technology. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-spinner` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `status`. Name from: aria-label.

| ARIA attribute | When |
| --- | --- |
| `aria-hidden` | The spinner has no label. |
| `aria-label` | The spinner has a label. |

**Tokens:** —

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `spinner.decorative` | A spinner that only decorates. |
| `spinner.labelled` | A spinner that tells that content is loading. |

## label

The visible name of a control.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `required` | boolean | `false` | Adds a mark that tells the reader that the control must be filled in. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The text of the label. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-label` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: none. Name from: content.

**Tokens:** `color-text`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `label.default` | A label. |
| `label.required` | A label of a control that must be filled in. |

## error

The message that says why a value is refused.

**Script:** none.

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The text of the message. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-error` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `alert`. Name from: none.

**Tokens:** `color-danger-text`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `error.default` | An error message. |

## field

A control with its label, its hint and its error, tied together.

**Script:** none.

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The control. The first form control of this slot is the one that the field ties to its label, its hint and its error. A control that draws its own label, hint or error, such as a checkbox or a switch, is not put in a field. |
| `label` | no | The label of the control. |
| `hint` | no | A line that helps to fill the control in. |
| `error` | no | The message that says why the value is refused. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-field` | root | The root element. |
| `data-studeo-invalid` | state | See the states of the specification. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: none. Name from: none.

| ARIA attribute | When |
| --- | --- |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |
| `aria-invalid` | On the control, while the error is showing. |

**Tokens:** —

**Composes:** `label`, `error`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `field.default` | A field with a label and a hint. |
| `field.invalid` | A field whose value is refused. |

## input

A control for a line of text.

**Script:** optional. The reveal button of a password switches the type of the control; every other use needs no script. Without the script the reveal button does nothing and the password stays hidden.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `outline`, `filled` | `outline` | Whether the control is outlined (a border and the fill of a field) or filled (the surface colour, with a border that is clear only against the page: not for a control inside a card). |
| `size` | one of `sm`, `md`, `lg` | `md` | The size of the control. |
| `type` | one of `text`, `email`, `password`, `search`, `tel`, `url`, `number` | `text` | The kind of value. |
| `label` | string |  | The compact form: a label, which builds the field around the control. |
| `hint` | string |  | The compact form: a line that helps to fill the control in. |
| `error` | string |  | The compact form: the message that says why the value is refused. It also marks the control as invalid. |
| `icon` | icon |  | The icon shown before the text. The slot of the same name replaces it. |
| `icon-trailing` | icon |  | The icon shown after the text. The slot of the same name replaces it. |
| `viewable` | boolean | `false` | For a password: adds a button that shows and hides the value. |
| `disabled` | boolean | `false` | The control cannot be changed or focused. |
| `readonly` | boolean | `false` | The value can be read and selected but not changed. |
| `loading` | boolean | `false` | The control shows that something is in progress. |
| `control-class` | class |  | Classes for the element that does the work, merged with its own. The author's classes win over them. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `icon` | no | Any content in place of the icon before the text. |
| `icon-trailing` | no | Any content in place of the icon after the text. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-input` | root | The root element. |
| `data-studeo-input-control` | part | The element that takes the text. |
| `data-studeo-input-reveal` | part | The button that shows and hides a password. Its accessible name is Show password. |
| `data-studeo-input-icon` | part | The element that holds the icon before the text. |
| `data-studeo-input-icon-trailing` | part | The element that holds the icon after the text. |
| `data-studeo-input-error` | part | The element that holds the error message of the compact form. |
| `data-studeo-invalid` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |
| `data-studeo-readonly` | state | See the states of the specification. |
| `data-studeo-loading` | state | See the states of the specification. |

Attributes that an author writes on the component go to `data-studeo-input-control`.

**Accessibility**

Role: `textbox`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Enter` | Submits the form that holds the control. |
| `Enter or Space on the reveal button` | Shows or hides the password. |

| ARIA attribute | When |
| --- | --- |
| `aria-invalid` | On the control, while the error is showing. |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |
| `aria-busy` | On the control, while it is loading. |
| `aria-pressed` | On the reveal button: true while the password is shown. |

**Tokens:** `color-surface`, `color-border-strong`, `color-field`, `color-text`, `color-text-muted`, `color-focus-ring`, `color-danger-text`, `radius-control`

**Composes:** `field`, `label`, `error`, `icon`, `button`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `input.default` | A labelled input for an address. |
| `input.hint` | An input with a hint. |
| `input.invalid` | An input whose value is refused. |
| `input.disabled` | An input that is switched off. |
| `input.loading` | An input while something is in progress. |
| `input.readonly` | An input whose value can be read but not changed. |
| `input.password` | A password with the button that shows it. |
| `input.with-icon` | An input with an icon before the text. |
| `input.bare` | A control with no label of its own, to be placed in a field. |

## checkbox

A control that turns one choice on or off.

**Script:** optional. The mixed state of a checkbox can be set only through a property of the element, which takes script. Keeping the state attributes of the root in step with the control after the user changes it takes a small script too; every other use needs none. Without the script a mixed checkbox is announced as mixed but is drawn empty.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string |  | The compact form: the text of the label, built next to the box. |
| `hint` | string |  | The compact form: a line that explains the choice. |
| `error` | string |  | The compact form: the message that says why the choice is refused. It also marks the control as invalid. |
| `checked` | boolean | `false` | Whether the choice starts on. |
| `indeterminate` | boolean | `false` | The choice is neither on nor off, as for the parent of a set that is partly chosen. |
| `disabled` | boolean | `false` | The control cannot be changed or focused. |
| `control-class` | class |  | Classes for the element that does the work, merged with its own. The author's classes win over them. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | Any content in place of the text of the label. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-checkbox` | root | The root element. |
| `data-studeo-checkbox-control` | part | The native element that holds the choice. |
| `data-studeo-checkbox-box` | part | The drawn box with its mark. |
| `data-studeo-checkbox-label` | part | The element that holds the label and the hint. |
| `data-studeo-checkbox-error` | part | The element that holds the error message. |
| `data-studeo-checked` | state | See the states of the specification. |
| `data-studeo-indeterminate` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |
| `data-studeo-invalid` | state | See the states of the specification. |

Attributes that an author writes on the component go to `data-studeo-checkbox-control`.

**Accessibility**

Role: `checkbox`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Space` | Turns the choice on or off. |

| ARIA attribute | When |
| --- | --- |
| `aria-checked` | On the control: mixed while the choice is indeterminate. |
| `aria-invalid` | On the control, while the error is showing. |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |

**Tokens:** `color-accent`, `color-accent-foreground`, `color-border-strong`, `color-surface`, `color-focus-ring`, `color-text`, `color-danger-text`, `radius-control`

**Composes:** `icon`, `label`, `error`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `checkbox.default` | A checkbox that is off. |
| `checkbox.checked` | A checkbox that is on. |
| `checkbox.indeterminate` | A checkbox that is neither on nor off. |
| `checkbox.disabled` | A checkbox that is switched off. |
| `checkbox.invalid` | A checkbox whose choice is refused. |
| `checkbox.with-hint` | A checkbox with a hint. |

## switch

A control that turns a setting on or off at once.

**Script:** optional. The control, the keyboard and the form work without script. Keeping the state attribute of the root in step with the control after the user changes it takes a small script. A switch that is loading must not change by any route, and a native control cannot ignore the keyboard without script: the Space key and a click on a loading switch are ignored by script.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string |  | The compact form: the text of the label, built next to the switch. |
| `hint` | string |  | The compact form: a line that explains the setting. |
| `error` | string |  | The compact form: the message that says why the change is refused. It also marks the control as invalid. |
| `checked` | boolean | `false` | Whether the setting starts on. |
| `disabled` | boolean | `false` | The control cannot be changed or focused. |
| `loading` | boolean | `false` | The change is being saved. |
| `control-class` | class |  | Classes for the element that does the work, merged with its own. The author's classes win over them. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | Any content in place of the text of the label. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-switch` | root | The root element. |
| `data-studeo-switch-control` | part | The native element that holds the setting. |
| `data-studeo-switch-track` | part | The drawn track. |
| `data-studeo-switch-thumb` | part | The drawn thumb that moves along the track. |
| `data-studeo-switch-label` | part | The element that holds the label and the hint. |
| `data-studeo-switch-error` | part | The element that holds the error message. |
| `data-studeo-checked` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |
| `data-studeo-invalid` | state | See the states of the specification. |
| `data-studeo-loading` | state | See the states of the specification. |

Attributes that an author writes on the component go to `data-studeo-switch-control`.

**Accessibility**

Role: `switch`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Space` | Turns the setting on or off. |

| ARIA attribute | When |
| --- | --- |
| `aria-busy` | On the control, while the change is being saved. |
| `aria-invalid` | On the control, while the error is showing. |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |

**Tokens:** `color-accent`, `color-border-strong`, `color-surface`, `color-focus-ring`, `color-text`, `color-danger-text`, `radius-pill`

**Composes:** `label`, `error`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `switch.default` | A switch that is off. |
| `switch.checked` | A switch that is on. |
| `switch.disabled` | A switch that is switched off. |
| `switch.loading` | A switch while its change is saved. |
| `switch.invalid` | A switch whose change is refused. |
| `switch.with-hint` | A switch with a hint. |

## code-input

A control for a short code, such as the code of an authenticator app or a recovery code.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `length` | integer | `6` | The number of characters of the code. |
| `kind` | one of `numeric`, `alphanumeric` | `numeric` | Whether the code is made of digits only or of letters and digits. |
| `label` | string |  | The compact form: a label, which builds the field around the control. |
| `hint` | string |  | The compact form: a line that helps to fill the control in. |
| `error` | string |  | The compact form: the message that says why the code is refused. It also marks the control as invalid. |
| `disabled` | boolean | `false` | The control cannot be changed or focused. |
| `loading` | boolean | `false` | The code is being checked. |
| `control-class` | class |  | Classes for the element that does the work, merged with its own. The author's classes win over them. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-code-input` | root | The root element. |
| `data-studeo-code-input-control` | part | The one native element that takes the whole code. Its look of separate boxes is made by style, so that the code can be pasted and filled in by the browser. |
| `data-studeo-code-input-error` | part | The element that holds the error message. |
| `data-studeo-invalid` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |
| `data-studeo-loading` | state | See the states of the specification. |

Attributes that an author writes on the component go to `data-studeo-code-input-control`.

**Accessibility**

Role: `textbox`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Enter` | Submits the form that holds the control. |

| ARIA attribute | When |
| --- | --- |
| `aria-invalid` | On the control, while the error is showing. |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |
| `aria-busy` | On the control, while the code is being checked. |

**Tokens:** `color-border-strong`, `color-field`, `color-text`, `color-focus-ring`, `color-danger-text`, `radius-control`, `font-family-mono`

**Composes:** `label`, `error`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `code-input.default` | A code input for a numeric code. |
| `code-input.hint` | A code input with a hint. |
| `code-input.invalid` | A code input whose code is refused. |
| `code-input.alphanumeric` | A code input for a recovery code. |
| `code-input.disabled` | A code input that is switched off. |
| `code-input.loading` | A code input while the code is being checked. |

## heading

A title. Its level in the outline of the page is set apart from its size.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `level` | integer | `2` | The level in the outline of the page, from 1 to 6. It does not change the size. |
| `size` | one of `sm`, `md`, `lg`, `xl` | `md` | How large the title is drawn. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The text of the title. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-heading` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `heading`. Name from: content.

**Tokens:** `color-text`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `heading.default` | A title at level two. |
| `heading.page-title` | A title at level one, drawn large. |
| `heading.small` | A title at level three, drawn small. |

## text

A paragraph of running text.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `default`, `muted` | `default` | Whether the text has the main colour or the muted one. |
| `size` | one of `sm`, `md`, `lg` | `md` | How large the text is drawn. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The text. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-text` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: none. Name from: content.

**Tokens:** `color-text`, `color-text-muted`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `text.default` | A paragraph. |
| `text.muted` | A paragraph in the muted colour. |

## link

A way to go to another address. It marks the place the user is in.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `href` | url |  | The address: a relative address, or one that starts with http, https, mailto or tel. Any other address, such as one that runs a script, is not used. A link with no address, or with an address that is not used, is a disabled link: it is shown without an address, it is not a link for assistive technology, it cannot take the focus and it does not take the pointer. |
| `variant` | one of `default`, `subtle`, `button` | `default` | Whether the link is underlined and coloured, has the colour of the text around it, or is drawn like a button of the default variant. A disabled link of the variant button is subdued. |
| `current` | boolean | `false` | The address is the place the user is in: the link is marked as the current page. |
| `external` | boolean | `false` | The address opens in a new window or tab, and the link says so. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The text of the link. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-link` | root | The root element. |
| `data-studeo-link-external` | part | The text that tells assistive technology that the address opens in a new window or tab. It is hidden from sight. The words are a text of the implementation that a host can translate; in English they are: Opens in a new tab. |
| `data-studeo-current` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `link`. Name from: content.

| Key | Behavior |
| --- | --- |
| `Enter` | Follows the link. |

| ARIA attribute | When |
| --- | --- |
| `aria-current` | The link is the current place. The value is page. |
| `aria-disabled` | The link has no address that is used: it is then not a link, it carries the disabled state and the value is true. |

**Tokens:** `color-accent-text`, `color-text`, `color-text-muted`, `color-surface`, `color-border`, `color-border-strong`, `color-accent-subtle`, `color-focus-ring`, `radius-control`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `link.default` | A link. |
| `link.current` | The link of the place the user is in. |
| `link.external` | A link that opens in a new window or tab. |
| `link.subtle` | A link in the colour of its text. |
| `link.button` | A link that is drawn like a button. |
| `link.disabled` | A link with no address. |
| `link.button-disabled` | A link with no address that is drawn like a button. |

## alert

A message that stays in the page until the situation changes or the user dismisses it.

**Script:** optional. Hiding the alert at the press of the dismiss button needs a small script, unless the page is drawn again by the server. Without the script the dismiss button does nothing and the alert stays.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `info`, `success`, `warning`, `danger` | `info` | What the message is about. |
| `icon` | icon |  | The icon shown before the message. Without it the alert shows the icon of its variant. The slot of the same name replaces it. |
| `dismissible` | boolean | `false` | Adds a button that hides the alert. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The message. |
| `title` | no | A short line above the message. |
| `icon` | no | Any content in place of the icon before the message. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-alert` | root | The root element. |
| `data-studeo-alert-icon` | part | The element that holds the icon. |
| `data-studeo-alert-title` | part | The element that holds the title. |
| `data-studeo-alert-body` | part | The element that holds the message. |
| `data-studeo-alert-dismiss` | part | The button that hides the alert. Its accessible name is Dismiss. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `status`. Name from: content.

| Key | Behavior |
| --- | --- |
| `Enter` | On the dismiss button: hides the alert. |
| `Space` | On the dismiss button: hides the alert. |

**Tokens:** `color-info-subtle`, `color-info-text`, `color-success-subtle`, `color-success-text`, `color-warning-subtle`, `color-warning-text`, `color-danger-subtle`, `color-danger-text`, `color-focus-ring`, `radius-container`

**Composes:** `icon`, `button`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `alert.default` | An informing message. |
| `alert.success` | A message that something worked. |
| `alert.warning` | A message that asks for care. |
| `alert.danger` | A message that something failed. |
| `alert.with-title` | A message with a title. |
| `alert.dismissible` | A message that the user can hide. |

## card

A surface that groups what belongs together: a header, a body and a footer.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `outline`, `raised` | `outline` | Whether the card is set off by a border or by a shadow. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The body. |
| `header` | no | What stands above the body, such as a title. |
| `footer` | no | What stands below the body, such as actions or links. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-card` | root | The root element. |
| `data-studeo-card-header` | part | The element that holds the header. |
| `data-studeo-card-body` | part | The element that holds the body. |
| `data-studeo-card-footer` | part | The element that holds the footer. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: none. Name from: none.

**Tokens:** `color-surface`, `color-border`, `radius-container`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `card.default` | A card with a body. |
| `card.with-header-and-footer` | A card with a header, a body and a footer. |
| `card.raised` | A card set off by a shadow. |

## table

Rows and columns of data, with a caption and a message for when there are no rows.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `plain`, `striped` | `plain` | Whether every row looks the same or the rows alternate. |
| `loading` | boolean | `false` | The rows are being replaced. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | The rows of the body. |
| `head` | no | The column headers. The table puts them in one row. |
| `caption` | no | A line that names the table. |
| `empty` | no | The message that is shown when the body has no rows. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-table` | root | The root element. |
| `data-studeo-table-caption` | part | The element that holds the caption. |
| `data-studeo-table-head` | part | The element that holds the row of column headers. |
| `data-studeo-table-body` | part | The element that holds the rows. |
| `data-studeo-table-empty` | part | The element that holds the message shown when there are no rows. |
| `data-studeo-loading` | state | See the states of the specification. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `table`. Name from: associated-label.

| ARIA attribute | When |
| --- | --- |
| `aria-busy` | While the rows are being replaced. |

**Tokens:** `color-surface`, `color-border`, `color-text`, `color-text-muted`, `color-accent-subtle`, `radius-container`

**Composes:** `table-column`, `table-row`, `table-cell`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `table.bare` | A table with nothing in it: the place where a column, a row or a cell is shown by itself. |
| `table.default` | A table with a column header and a row. |
| `table.striped` | A table whose rows alternate. |
| `table.empty` | A table with no rows. |
| `table.loading` | A table whose rows are being replaced. |

## table-column

The header of one column. When the list can be sorted by the column it is a link to the address that sorts by it.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `href` | url |  | The address that sorts the list by this column. Without it the column cannot be sorted by. |
| `sorted` | one of `ascending`, `descending` |  | The direction in which the list is now sorted by this column. Without it the list is not sorted by this column. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The name of the column. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-table-column` | root | The root element. |
| `data-studeo-table-column-link` | part | The link that sorts the list. It holds the name of the column and, when the list is sorted by the column, the chevron that shows the direction. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `columnheader`. Name from: content.

| Key | Behavior |
| --- | --- |
| `Enter` | On the link of a sortable column: follows it. |

| ARIA attribute | When |
| --- | --- |
| `aria-sort` | While the list is sorted by this column. The value is ascending or descending. |

**Tokens:** `color-text`, `color-text-muted`, `color-focus-ring`

**Composes:** `icon`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `table-column.default` | A column that cannot be sorted by. |
| `table-column.sortable` | A column that the list can be sorted by. |
| `table-column.ascending` | A column that the list is sorted by, from first to last. |
| `table-column.descending` | A column that the list is sorted by, from last to first. |

## table-row

One row of the body: the cells of one record.

**Script:** none.

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | The cells of the row. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-table-row` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `row`. Name from: none.

**Tokens:** `color-border`, `color-accent-subtle`

**Composes:** `table-cell`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `table-row.bare` | A row with no cell: the place where a cell is shown by itself. |
| `table-row.default` | A row with a cell. |

## table-cell

One cell of a row.

**Script:** none.

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | What the cell shows. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-table-cell` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `cell`. Name from: content.

**Tokens:** `color-text`

**Composes:** —

**Fixtures**

| Fixture | Description |
| --- | --- |
| `table-cell.default` | A cell with a text. |

## data-table

A table of a long list that the server pages, sorts and filters, with a search, filters and paging controls.

**Script:** optional. The search, the sorting and the paging work without script: the search is a form and the sort and the pages are links. Sending a new page size at once, when it is chosen, takes a small script.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | required | The name of the list, for assistive technology. |
| `search` | string |  | The text that the list is now searched for. It fills the search field. |
| `searchable` | boolean | `true` | Whether the search field is shown. |
| `sort` | string |  | The sort the list answer was made with, as the server names it. A minus sign at the start asks for the descending order. It is sent under the name sort, with the search. |
| `current-page` | integer | `1` | The page shown (current_page of the answer). |
| `last-page` | integer | `1` | The last page (last_page of the answer). |
| `per-page` | integer | `25` | The number of rows on a page (per_page of the answer). |
| `per-page-options` | list | `[10,25,50,100]` | The page sizes that the server accepts, as whole numbers. They fill the choice of the page size. When it is left out, the sizes are 10, 25, 50 and 100. |
| `total` | integer | `0` | The number of rows in all pages (total of the answer). When it is zero the empty message is shown. |
| `first-url` | url |  | The address of the first page (first of the links of the answer). Left out when there is none. |
| `last-url` | url |  | The address of the last page. Left out when there is none. |
| `previous-url` | url |  | The address of the previous page. Left out when there is none. |
| `next-url` | url |  | The address of the next page. Left out when there is none. |
| `loading` | boolean | `false` | Another page is being loaded. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | The table. |
| `filters` | no | The controls that narrow the list, such as a choice of status. |
| `actions` | no | What stands at the end of the toolbar, such as a button that adds a row. |
| `empty` | no | The message that is shown when the list has no rows. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-data-table` | root | The root element. |
| `data-studeo-data-table-toolbar` | part | The element that holds the search, the filters and the actions. |
| `data-studeo-data-table-search` | part | The field that takes the text to search for. Its accessible name is Search. It is sent under the name search, and sending it asks the server for the first page. |
| `data-studeo-data-table-empty` | part | The element that holds the message shown when the list has no rows. |
| `data-studeo-data-table-summary` | part | The line that says how many rows there are and which page is shown, as: 60 results, page 1 of 3. |
| `data-studeo-data-table-per-page` | part | The choice of how many rows a page holds. Its accessible name is Rows per page. It is a select. It is sent under the name per_page, and it offers the sizes of the prop per-page-options. |
| `data-studeo-data-table-first` | part | The control to the first page: a link, or a disabled link while there is no such page. Its accessible name is First page. |
| `data-studeo-data-table-previous` | part | The control to the previous page: a link, or a disabled link while there is no such page. Its accessible name is Previous page. |
| `data-studeo-data-table-next` | part | The control to the next page: a link, or a disabled link while there is no such page. Its accessible name is Next page. |
| `data-studeo-data-table-last` | part | The control to the last page: a link, or a disabled link while there is no such page. Its accessible name is Last page. |
| `data-studeo-loading` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. Carried by the parts `data-studeo-data-table-first`, `data-studeo-data-table-previous`, `data-studeo-data-table-next`, `data-studeo-data-table-last`. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `region`. Name from: aria-label.

| Key | Behavior |
| --- | --- |
| `Enter` | On the search field: sends the search. On a paging control that has an address: follows it. |

| ARIA attribute | When |
| --- | --- |
| `aria-busy` | On the root, while another page is being loaded. |
| `aria-live` | On the summary, with the value polite, so that a change of page is announced. |
| `aria-disabled` | On a paging control that has no address: it is not a link and it carries the disabled state. |

**Tokens:** `color-surface`, `color-border`, `color-text`, `color-text-muted`, `radius-container`

**Composes:** `table`, `input`, `select`, `link`, `icon`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `data-table.default` | The first of three pages. |
| `data-table.last-page` | The last of three pages. |
| `data-table.sizes` | A list that offers its own page sizes. |
| `data-table.sorted` | A list sorted from last to first by name. |
| `data-table.searched` | A list that was searched for a text. |
| `data-table.empty` | A list with no rows. |
| `data-table.no-results` | A list with no rows and no words of its own: the component writes its default message. |
| `data-table.loading` | A list while another page is loading. |

## dialog

A window over the page that asks for the user's attention until it is closed. While it is open, the rest of the page cannot be used.

**Script:** optional. Making the dialog modal takes a small script, whether it is open when the page loads or is opened by a button, unless the stack opens it by what the platform provides for that. Closing it, the focus trap and the Escape key then come from the platform. When the dialog closes, the focus returns to what opened it. Without the script an open dialog is not modal: the page behind it can still be used and Escape does nothing, but its close button still works, because it closes the dialog by the platform.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `open` | boolean | `false` | Whether the dialog is open. |
| `size` | one of `sm`, `md`, `lg` | `md` | How wide the dialog is: narrow, medium or wide. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `title` | yes | The title. It is the name of the dialog. |
| `default` | yes | The body. |
| `footer` | no | The actions, such as the buttons that confirm and cancel. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-dialog` | root | The root element. |
| `data-studeo-dialog-title` | part | The element that holds the title. |
| `data-studeo-dialog-body` | part | The element that holds the body. |
| `data-studeo-dialog-footer` | part | The element that holds the actions. |
| `data-studeo-dialog-close` | part | The button that closes the dialog. Its accessible name is Close. |
| `data-studeo-open` | state | See the states of the specification. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `dialog`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Escape` | Closes the dialog. |
| `Tab` | Moves the focus among the controls of the dialog and never out of it. |
| `Enter` | On the close button: closes the dialog. |

| ARIA attribute | When |
| --- | --- |
| `aria-modal` | While the dialog is open, with the value true. |
| `aria-labelledby` | Naming the title. |

**Tokens:** `color-surface`, `color-border`, `color-overlay`, `color-text`, `color-focus-ring`, `radius-container`

**Composes:** `button`, `icon`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `dialog.default` | An open dialog with a title and a body. |
| `dialog.with-footer` | An open dialog with actions. |
| `dialog.closed` | A dialog that is not open. |
| `dialog.small` | An open dialog that is narrow. |

## toast

A short message that appears over the page to confirm something. A message of success or information hides itself after a few seconds.

**Script:** required. The timer that hides the toast, and its pause while the pointer is over the toast or the focus is inside it, cannot be done by style.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `info`, `success`, `warning`, `danger` | `info` | What the message is about. |
| `duration` | integer | `5` | How many seconds a message of success or information stays before it hides itself. Zero keeps it until it is dismissed. It has no effect on warning and danger: they never hide themselves. |
| `icon` | icon |  | The icon shown before the message. Without it the toast shows the icon of its variant. The slot of the same name replaces it. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | yes | The message. |
| `title` | no | A short line above the message. |
| `icon` | no | Any content in place of the icon before the message. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-toast` | root | The root element. |
| `data-studeo-toast-icon` | part | The element that holds the icon. |
| `data-studeo-toast-title` | part | The element that holds the title. |
| `data-studeo-toast-body` | part | The element that holds the message. |
| `data-studeo-toast-dismiss` | part | The button that hides the toast. Its accessible name is Dismiss. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `status`. Name from: content.

| Key | Behavior |
| --- | --- |
| `Enter` | On the dismiss button: hides the toast. |
| `Space` | On the dismiss button: hides the toast. |

**Tokens:** `color-info-subtle`, `color-info-text`, `color-success-subtle`, `color-success-text`, `color-warning-subtle`, `color-warning-text`, `color-danger-subtle`, `color-danger-text`, `color-focus-ring`, `radius-container`

**Composes:** `icon`, `button`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `toast.success` | A confirmation that hides itself. |
| `toast.info` | A piece of information that hides itself. |
| `toast.persistent` | A confirmation that stays until it is dismissed. |
| `toast.danger` | A message of failure, which never hides itself. |
| `toast.with-title` | A toast with a title. |

## toast-region

The place at the bottom end of the page where toasts appear, one after another.

**Script:** required. Showing at most a number of toasts and queueing the others cannot be done by style. A host raises a toast by dispatching the event studeo:toast on the window with a detail that holds variant, title, message and duration; the region makes the toast, so that no stack needs a call of its own. The scenarios cannot express an event, so each UI package tests it itself.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `max` | integer | `3` | The most toasts that show at once. The others wait and appear as room is made. |

**Slots**

| Slot | Required | Description |
| --- | --- | --- |
| `default` | no | The toasts. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-toast-region` | root | The root element. |

Attributes that an author writes on the component go to the root element.

**Accessibility**

Role: `region`. Name from: aria-label.

| ARIA attribute | When |
| --- | --- |
| `aria-live` | Always, with the value polite, so that a toast that appears is announced without interrupting the user. Its accessible name is Notifications. |

**Tokens:** —

**Composes:** `toast`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `toast-region.default` | A region that holds a toast. |
| `toast-region.empty` | A region with no toast. |

## select

A control that picks one value from a list of options. The closed control looks the same everywhere; the open list is the platform's.

**Script:** none.

**Props**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | one of `outline`, `filled` | `outline` | Whether the control is outlined (a border and the fill of a field) or filled (the surface colour, with a border that is clear only against the page: not for a control inside a card). |
| `size` | one of `sm`, `md`, `lg` | `md` | The size of the control. |
| `options` | list | required | The options to choose from. Each item is a text or a whole number, which is its own label, or an object with a value and a label. |
| `value` | string |  | The value of the option that is chosen. When it is left out, the placeholder shows, or else the first option. |
| `placeholder` | string |  | The text shown while no option is chosen. It is not an option that can be chosen again. |
| `label` | string |  | The compact form: a label, which builds the field around the control. |
| `hint` | string |  | The compact form: a line that helps to choose. |
| `error` | string |  | The compact form: the message that says why the choice is refused. It also marks the control as invalid. |
| `disabled` | boolean | `false` | The control cannot be changed or focused. |
| `loading` | boolean | `false` | The control shows that something is in progress. |
| `control-class` | class |  | Classes for the element that does the work, merged with its own. The author's classes win over them. |

**Attributes**

| Attribute | Kind | Description |
| --- | --- | --- |
| `data-studeo-select` | root | The root element. |
| `data-studeo-select-control` | part | The native element that holds the choice. |
| `data-studeo-select-chevron` | part | The icon at the end of the control that shows that a list opens. |
| `data-studeo-select-error` | part | The element that holds the error message of the compact form. |
| `data-studeo-invalid` | state | See the states of the specification. |
| `data-studeo-disabled` | state | See the states of the specification. |
| `data-studeo-loading` | state | See the states of the specification. |

Attributes that an author writes on the component go to `data-studeo-select-control`.

**Accessibility**

Role: `combobox`. Name from: associated-label.

| Key | Behavior |
| --- | --- |
| `Space` | Opens the list of options. |
| `ArrowDown or ArrowUp` | Moves to the next or the previous option. |

| ARIA attribute | When |
| --- | --- |
| `aria-invalid` | On the control, while the error is showing. |
| `aria-describedby` | On the control, naming the hint and the error that are showing. |
| `aria-busy` | On the control, while it is loading. |

**Tokens:** `color-surface`, `color-border-strong`, `color-field`, `color-text`, `color-text-muted`, `color-focus-ring`, `color-danger-text`, `radius-control`

**Composes:** `field`, `label`, `error`, `icon`

**Fixtures**

| Fixture | Description |
| --- | --- |
| `select.default` | A labelled select with options that have labels. |
| `select.chosen` | A select with one option chosen. |
| `select.plain` | A select whose options are whole numbers. |
| `select.placeholder` | A select with a placeholder and no choice. |
| `select.hint` | A select with a hint. |
| `select.invalid` | A select whose choice is refused. |
| `select.disabled` | A select that is switched off. |
| `select.loading` | A select while something is in progress. |
| `select.bare` | A control with no label of its own, to be placed in a field. |
<!-- /generated:reference -->
