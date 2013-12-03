## Requirements

- PHP 5.3
- lithium 0.11 or newer

## Installation

Checkout the code to either of your library directories:

    $ cd libraries
    $ git clone git@github.com:bruensicke/radium.git

Include the library in your `/app/config/bootstrap/libraries.php`

    Libraries::add('radium');

## Installation via composer

If you are using composer to manage your projects dependencies, you can add radium to your requirements like that:

    {
        "require": {
            "bruensicke/radium": "dev-master"
        }
    }

