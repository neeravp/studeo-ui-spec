# Changelog

Breaking and notable changes per release.

## [Unreleased]

- The first draft: the manifest, the schemas, the tokens, the components, the scenarios and the documents.
- The scenarios have one reading: the specification says how the target of a step is found (the whole accessible name, exactly one element) and what each step means (a text is contained, an attribute without a value is present, hidden includes no match at all).
- The button names every token it reads: the colours of its variants and of its disabled state, the spacing steps of its padding and its gap.
- The `spinner` component: a turning picture that shows that something is loading, with the size and the label of the icon, the role `status`, and two fixtures. A loading button shows it in place of its icon.
- The tokens of a component are the tokens that it reads at least: a UI package may read other tokens of the specification too.
- A part may list the states it can be in, in the schema and in the checks: the paging controls of a data table carry the disabled state, and the table no longer declares it.
- The `Script` lines say what the scenarios exercise: the switch, the checkbox, the dialog and the data table need a small script for one named part of their behaviour (`optional`); the specification now says what `none`, `optional` and `required` mean.
- The `href` of a link says which addresses are used, and the words that an external link says to assistive technology are a text that a host can translate.
- A table is built from native parts: the `table-row` and the `table-cell` are components (the parts `data-studeo-table-row` and `data-studeo-table-cell` moved from the table to them), a slot of a fixture may hold a list of fixtures, and a fixture of a component that cannot stand alone names the fixture that holds it (`in`).
- A component that sends attributes to a part (the input, the checkbox, the switch and the code input) takes the prop `control-class`, for classes on that part.
- The contract of the toast region says how a toast is raised: the event `studeo:toast` on the window.
- The `Script` lines of the switch, the checkbox, the input, the alert and the dialog say what is lost without script (a loading switch ignores the keyboard by script), and a new step, `expectNoAttribute`, lets a scenario check that an element does not carry an attribute.
- The dialog has a token for each of its three widths (`size-container-narrow`, `size-container-medium`, `size-container-wide`).
- The `field` says which control it ties (the first form control of its default slot) and which controls are not put in a field; a scenario checks the description and the invalidity of the control together.
- Props may be lists: the type `list` holds texts, whole numbers or options with a value and a label (a fixture may set it); the new `select` component is a native control that picks one option, and the data table offers its page sizes through it (`per-page-options`).
- A link may be drawn like a button (`variant` `button`), and a link with no address is a disabled link (`aria-disabled`, the state `data-studeo-disabled`); the paging controls of a data table are links, and the data table names the fields it sends (`search`, `sort`, `per_page`).
- The words that components write themselves are one list in the data (`messages`, with their placeholders and the form for one), each written by exactly one component, and a component declares the CSS variables that it sets for its own classes (`variables`); an implementation derives its English words and the list of its variables from them.
- The data table has a fixture with no rows and no words of its own (`data-table.no-results`) and a scenario that expects the default message, so the words that the component writes when the author gives none are exercised.
- A control is found by its fill as well as by its border: the new token `color-field` is the fill of a text control (the input, the select and the code input read it), one step above the surface, and the border of a control (`color-border-strong`) is softer but keeps at least the contrast that the specification requires against the page, the surface and the field; five pairs of colours state it.
- The code input reads the field fill and no longer lists the surface colour among its tokens, so that an implementation does not have to read a token it does not use.
