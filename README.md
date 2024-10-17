# Silverstripe Userform Columns

[![Latest Stable Version](https://poser.pugx.org/iliain/userform-columns/v)](https://packagist.org/packages/iliain/userform-columns) 
[![Total Downloads](https://poser.pugx.org/iliain/userform-columns/downloads)](https://packagist.org/packages/iliain/userform-columns) 
[![Latest Unstable Version](https://poser.pugx.org/iliain/userform-columns/v/unstable)](https://packagist.org/packages/iliain/userform-columns) 
[![License](https://poser.pugx.org/iliain/userform-columns/license)](https://packagist.org/packages/iliain/userform-columns) 
[![PHP Version Require](https://poser.pugx.org/iliain/userform-columns/require/php)](https://packagist.org/packages/iliain/userform-columns)

Duplicates the Userform Field Group feature to allow adding rows & columns to forms

## Installation (with composer)

	$ composer require iliain/userform-columns

## Requirements

Silverstripe Userforms 5+ or 6+

## Config

Customise the dropdown options for column classes via yaml:

```yaml
Iliain\UserformColumns\FormFields\EditableColumnStartField:
  css_classes:
    'col-12': 'Full width'
    'col-6': 'Half width'
    'col-4': 'Third width'
    'col-3': 'Quarter width'
```

You can also set the default classes for a row via yaml, or override the template:

```yaml
Iliain\UserformColumns\FormFields\EditableRowStartField:
  default_classes: 'flex flex-row w-full gap-2'
```
