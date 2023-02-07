# Countdown (Drupal Module)

This project implements a Drupal module providing countdown timers. A countdown timer is a widget that displays the remaining amount of time until a particular date and time.

## Installation

To install this module, require it using `composer` in your Drupal site project:

~~~bash
composer require tccl-drupal/countdown
~~~

Now you can enable the module using Drush (`drush en countdown`) or via the Modules interface in your Drupal site.

## Configuration

The module requires no additional configuration. You can begin to use the module immediately after installation.

## Usage

The `countdown` module provides countdown timers utilizing the following mechanisms:

- Token Replacement
- Field Type
- Render Element (low-level, programmatic)

### Token Replacement

You can render a countdown timer in any text field that is set up to filter tokens. The module provides a special token type that can be used for rendering countdown timers.

**General token format**:
~~~
[countdown:timer:YYYY-MM-DD HH:mm:SS:FORMAT:INCLUDE_SECONDS]
~~~
- The date must be given in the format above:
	- `YYYY`: the year
	- `MM`: the month of the year
	- `DD`: the day of the month
	- `HH`: the hour (in 24-hour format) of the day
	- `mm`: the minute after the hour
	- `ss`: the second after the minute
- The `FORMAT` is one of the formats described below
- `INCLUDE_SECONDS` is a boolean defaulting to `true`; you can use values such as `on`, `off`, `true`, `false`, `yes` and `no`

Example: countdown until Christmas 2023 using the `clock` format type:
~~~
[countdown:timer:2023-12-25 00:00:00:clock]
~~~

**Formats**:
| Format Specifier | Description | Example |
| -- | -- | -- |
| `clock` | Renders the countdown timer using a clock format. | `12:45:30` |
| `words` | Renders the countdown timer in words | `21 days, 8 hours, 0 minutes, 45 seconds` |
| `widget` | Renders the countdown timer using the `countdown-widget` theme template. (This is the same mechanism that renders the countdown field default formatter.) | `--` |

### Field Type

The module provides a field type that you can use to add new fields to a custom content type. The field is found under the `TCCL Custom` category and is called _Countdown_.

### Render Element

This low-level interface can be used by other modules to render countdown timers using the `countdown` and `countdown_inline` render elements.