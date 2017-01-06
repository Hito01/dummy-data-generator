# Dummy Data Generator

Wordpress plugin that generates dummy data using the [Faker](https://github.com/fzaninotto/Faker) PHP library. Also supports the awesome [ACF](https://www.advancedcustomfields.com) plugin.

## Installation

1. Drop the entire repo into your `wp-content/plugins` directory.
2. Enable the plugin.
3. Ensure you user has the capability `ddg_use` otherwise you won't be able to see the plugin in the menu.

## Usage

Go to the plugin page and fill the fields. Everything should be pretty straightforward.

## Supported ACF fields

At the moment, the following ACF fields are supported. Other will come in future releases.

* text
* textarea
* number
* email
* url
* password
* image
