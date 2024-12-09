# Wordpress Tailwind boilerplate
## What is in this package
This is a Wordpress theme boilerplate. The followings are already included:

- npm&vite 
- Tailwind
- Scss
- React
- PHP composer

Also the following features are put together
- CustomFields class with several form fields to be used as Custom Fields
- SearchEndpoint class to extend and customize the wp-api
- A not-styled React search component
- helpers: php and js simple helpers
- A sample custom post type class for extention

## Install

1. Git clone the theme in wp-content/themes/
2. make sure you have npm installed and inside the theme directory (hodcode) run `npm install`
3. From wordpress admin themes activate the `hodcode` theme.

### Development environment
For development first add the following const in wp-config.php in wordpress root:
`define("IS_VITE_DEVELOPMENT", true);`

Then run `npm run dev` inside theme directory. Now you can see css changes instantly on the 

### Production
in theme directory run `npm run build` 

make sure `IS_VITE_DEVELOPMENT` is false in wp-config.php and you should be good to go.

## Vscode PHP setup
To bypass wordpress functions errors in VScode when you load the theme directory only (instead of loading the project from wp root) you need to the followings:
1. Install `PHP Intelephense` vsode extention 
2. From menus goto File->Preferences->Settings
3. From the left panel on settings page goto Extentions->Intelephense and scroll down to reach `Intelephense: Stubs`
4. On list end press `Add Item` and add `wordpress`

By doing the this you should now be able to get suggestions and wp functions are now recognized as defined.