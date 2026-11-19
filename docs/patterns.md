# Patterns

The arrangements that components have in common. They are why a component that an author has never used before still feels familiar.

## Options and pass-through attributes

An option of a component is a prop: it is declared in the contract of the component and it sets the look or the behaviour. Anything else that an author writes on a component is an attribute, and the component hands it on to one of its elements. `customization.md` says to which.

## Look and scale

A component that has alternative looks takes a prop `variant`. A component that comes in sizes takes a prop `size`. Both are enums with a default, and their values are drawn from the same words in every component: `sm`, `md` and `lg` for the sizes. An author who has learned them on a button already knows them on an input.

## Compact form and full form

A component may offer a compact form: one prop that stands for parts the author would otherwise write out. A `label` prop on an input stands for the field, the label and the error around it. The compact form and the full form produce the same structure, so an author can begin with the compact form and move to the full one without the page changing.

## Sets and their members

A component that is used in sets has a part for the set and parts for its members. If it also makes sense on its own, the set is an optional wrapper. If it makes sense only in a set, the set carries the main name and the member is a part of it.

## Icon placement

A component that can show an icon takes it as a prop: `icon` puts it before the label and `icon-trailing` after it. The same two names are slots, for an author who wants something other than an icon of the stock; when a slot is filled it replaces the prop of the same name. The names of the icons that the specification relies on are listed in `components.json`. An implementation provides them, in any artwork, and may provide more. An icon that only decorates is hidden from assistive technology. A control that shows nothing but an icon must be given an accessible name.

## The field

A field consists of a label, a control, an optional hint and an optional error.

- The label is tied to the control: activating the label focuses the control, and the control is announced together with the label.
- The hint and the error are tied to the control in the way that assistive technology reads out as its description.
- While the error is showing, the control is marked as invalid.
- When an error appears, assistive technology announces it.

## Pending and switched off

A control with an action in progress shows that progress, is marked as busy and ignores activation, yet it can still be focused, so that the focus is not lost while the action runs. A control that is switched off cannot be activated and cannot be focused. These are different conditions, and a control can be in neither, either or both.

## Showing validation

A failed check appears in the error of its field and looks the same whether it came while the author was filling in the form or on submission. A form may check a field as soon as the author leaves it. The server remains the authority: it applies the same rules when the form is submitted and gives the same messages.

## Tables and long lists

A table with no rows shows why it is empty and, where it can, what to do next. The actions on a row sit in the last column of that row, and each has an accessible name that says which row it acts on.

A long list is paged, sorted and filtered by the server, and its state is kept in the address of the page, so that the page can be shared, bookmarked and returned to with the back button.

- The parameters carry the names of the list answer: `page`, `per_page`, `sort`, one for each filter, and `q` for text to search for. A minus sign at the start of the value of `sort` asks for the descending order.
- Changing the sort, a filter or the search takes the list back to its first page.
- The answer holds the rows in `data`, the paging in `meta` (`current_page`, `per_page`, `total`, `last_page`) and the addresses of the first, last, previous and next page in `links`, with no address where there is no such page. The component draws its controls from the answer and does not work out pages itself.
- While another page is loading, the table is marked as busy.
- A column that the list can be sorted by is a link to the address that sorts by it. The column that the list is sorted by says so to assistive technology, with its direction, ascending or descending. Following the link of the sorted column asks for the other direction.
- The controls to the first, previous, next and last page are links. A control that has no address, such as the one to a previous page on the first page, is not a link; it is marked as switched off.
- A line says how many rows there are and which page is shown, and assistive technology announces it when it changes.
- The page size is a choice among the sizes that the server accepts.

## A page for one task

A page that asks the user to do one thing, such as signing in, is composed from components and has no component of its own.

- It is one narrow column, centred in the page, no wider than 28rem, the width of the narrow dialog.
- At the top the application puts its own name or mark. Below it comes a card. Below the card the application may put a line of links, such as one to a page of help.
- The card holds the title of the page as a heading of level one, an optional line of text that says what is asked, the fields, and the button that sends them. A message about the whole form, such as one that says that the details are wrong, comes above the fields as an alert; a message about one field is the error of that field.
- The space between these parts is chosen by the page. The components bring no space of their own around them.
- The first field is where the focus starts when the page opens.

## Confirm an action

An action that cannot be undone, such as deleting an account, asks first, in a dialog.

- The title of the dialog names the action and what it acts on. The body says what will happen.
- The actions are two buttons: one that cancels and one that confirms. The confirming button has the danger variant and is named by the action, such as Delete account, never by OK or Yes.
- The focus starts on the button that cancels, so that pressing Enter by habit does no harm.
- Cancelling, pressing Escape and closing the dialog all do the same thing: nothing happens.

## Telling the result

What the user is told after an action depends on whether it needs care.

- A confirmation that something was saved is a toast of success. It hides itself after a few seconds and always has a dismiss button.
- A problem, or a warning, that the user must read and may need to act on is an alert in the page, or a toast of warning or danger. These never hide themselves.
- A host raises a toast by dispatching the event `studeo:toast` on the window, with a detail that holds the variant, the title, the message and the duration. The region makes the toast and queues it.
- A message about a field is the error of that field, not a toast.
