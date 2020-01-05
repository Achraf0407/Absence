# Laravel Enso's Changelog

## 3.7.4

This release improves the quality of the back-end code addressing issues revealed by [phpinsights](https://github.com/nunomaduro/) (more info in the back-end section) as well as the usual small updates and fixes.

### front-end

#### calendar
- added support for the spanish language

#### forms
- added in-page preview for PDF files
- fixed small styling issues and updated dependencies

#### forms
- now emits a `template-fetch-error` when there is an error fetching the template from the API

#### products
- added missing measurement-units package dependency

#### tables
- added support for row global actions

### backend-end

All packages were checked with the analitics tool and, where needed, the appropriate fixes and updates where implemented. 

Most packages have had updates pertaining to:
- missing constants visibility
- unused variables
- string concatenation vs string templates
- using old school methods such as `get_class` in favor of using `::class`
- inconsistent array indentation
- class fields and methods ordering

and more.

In addition to the code, architecture and style changes, the route files have been broken down into smaller files to make management easier.

Apart from these updates, which are too many to list individually for each package, the packages below have had other additions.

#### cli
- fixed the generated method name used in the table builder stub

#### core
- added the missing doctype to the production tag, which fixes a TinyMCE issue
- the User model deletion is now handled by the `AvoidsDeletionConflicts` trait 

#### countries
- added the country code to the model's resource

#### currencies
- fixed the Currency resource
- fixed the seeder for testing cases
- improved the converter so as to use the most recent date as fallback if there is no rate available for a given day
- `laravel-enso/countries` is now a dependency for the package
- the `short_name` attribute was refactored into `code`

#### products
- changed content type for the product part number form input from number to text

#### teams
- the Team model now uses the `AvoidsDeletionConflicts` trait for the delete process
- the `Relations` trait is utilized to be able to support dynamic methods

### Upgrade steps

This is a non breaking upgrade:

* run `composer update`
* run `yarn upgrade && yarn` in `/client`
* update the version to 3.7.4 in `config/enso/config.php`
* if using the `currencies` package with a prior version to `1.4.0`, also run `php artisan enso:upgrade` in order to update the `short_name` table column

## 3.7.3

This is the second to final release before upgrading Enso to PHP7.4.

### front-end

#### bulma
- made available the new InputFilter & EnsoInputFilter components
- made available the new SimpleDateFilter & EnsoSimpleDateFilter components

#### calendar
- updated EnsoCalendar's resizing strategy 

#### departments (new)
- a new package for managing departments

#### divider
- updated dependencies to address a security vulnerability

#### erd
- updated dependencies to address a security vulnerability

#### filters
- added two new filter components: InputFilter & SimpleDateFilter as well as their Enso counterparts: EnsoInputFilter & EnsoSimpleDateFilter
- fixed DateFilter's picker width
- date-filter refactor

#### how-to
- fixed the @enso-ui/uploader dependency restriction

#### measurement-units (new)
- a new package for managing measurement units

#### money
- updated dependencies to address a security vulnerability

#### people
- fixed the missing user buttons from person edit/create forms

#### projects (new)
- a new package for managing projects

#### tables
- you can now clear the search and change search mode while loading

#### themes
- updated deps to address mixin-deep security vulnerability
- updated the vue-filter css so that the components can be customized with is-* mod classes

#### uploader
- added the formatBytes.js helper
- fixed the file size reporting sometimes causing an undesired limitation

### back-end

#### core
- added a `enso:storage:reset` command which can be ran after php artisan migrate:fresh to clear the storage (but before seeding)

#### countries
- removed broken relationship
- added currencyCode in the model's resource
- this package is now a dependency of the new `laravel-enso/measurement-units` package

#### cli
- added the TableCache trait to the generated model
- fixed the request validator namespace 
- updated the `resources` folder to `client/src/`
- updated the tests

#### departments (new)
- a new package for managing a departments

#### forms
- adds the ability to customize routeParams on edit
- fixed several form template validation error messages

#### measurement-units
- a new package for managing measurement units
- this package is now a dependency of the `laravel-enso/products` package

#### localisation
- added missing translation keys

#### ocr (new)
- new package that can perform optical character recognition for pdf files
- depends on the [OCRmyPDF](https://github.com/jbarlow83/OCRmyPDF) package - you should read its documentation for more information, requirements and limitations

#### products
- extracted measurement unit functionality to a distinct package and updated related code
- removed the `measurement_unit` column from Product and replaces it with `measurement_unit_id`
- added the upgrade `php artisan enso:products:upgrade` console command which will migrate your products table and seed either a default `Piece` measurement unit or can use an optional Enum to seed the measurement units specified by the enum
- added some missing meta attributes within the table template

#### select
- fixed the typeahead trait causing issues when the request did non contain custom parameters
- refactored the search strategy resulting in more concise code
- fixed relation attribute search
- updated the display of the selected options, showing them at the bottom of the option list (previously they were at the top) making it easier to set new options when in multi-select mode

#### services
- refactored the paths and routes structure
- updated the factory and the tests

#### tables
- the exported file name is now translated
- fixed the searching logic when having nested columns (relations) on the main model
- refactored the search strategy resulting in more concise code

### Upgrade steps

This is a non breaking upgrade:

* run `composer update`
* run `yarn upgrade && yarn` in `/client`
* update the version to 3.7.3 in `config/enso/config.php`

If you are not using the `products` package, there is nothing else for you to do.

Otherwise, you should run the products package's upgrade command, considering the following:
- the measurement units functionality was extracted to the `measurement-units` package and `products` now depends on it
- measurement units were stored in the products table's `measurement_units` column, and the values were taken from the `MeasurementUnits` enum
- that column is dropped and replaced with a `measurment_unit_id` column which points to the `measurement_units` table

If you did not customize the enum and were using the defaults, then you can simply run
`php artisan enso:products:upgrade` and the command will handle the necessary changes.

If you had customized/extended the enum, then you should provide the enum as a parameter to the upgrade command, in which case it will be used to seed the new measurment units:

`php artisan enso:products:upgrade --enum=App\\Enums\\MyEnum`

## 3.7.2

The main purpose of this release was to switch from using [tiptap](https://github.com/scrumpy/tiptap) to using [tinyMCE](https://www.tiny.cloud/) for our what-you-see-is-what-you-get editor VueJS component. 

Through its dependency chain, after running `yarn upgrade` tiptap was breaking the build.

As a bonus, tinyMCE should be better supported and the VueJS wrapper components for it are slimmer and cleaner.

### front-end

#### wysiwyg
- drops tiptap in favour of tinyMCE (see more below)

#### forms
- updates the wyisiwyg field for the new tinyMCE powered wyisiwyg component

#### calendar
- reverts the resizing strategy

### back-end

#### companies
- within the static `owner` method the company model is resolved from the service container so that if you're extending & binding the package model to a local model, you will obtain the local instance

#### forms
- adds support for tinyMCE customization within the form configuration and the json templates
- fixes form params bug on create which caused the given parameter values to be ignored

#### localisation
- updated a couple of translation keys and values

### Upgrade steps

This is a non breaking upgrade:

* run `composer update`
* run `yarn upgrade && yarn` in `/client`
* update the version to 3.7.2 in `config/enso/config.php`
* update the `config/enso/forms.php` configuration file and add the new key:
```
|--------------------------------------------------------------------------
| TinyMCE Api Key
|--------------------------------------------------------------------------
| If you're using the wysiwyg field you need to get a free api key from 
| https://www.tiny.cloud/get-tiny/ first.
| 
*/

'tinyMCEApiKey' => env('TINY_MCE_API_KEY', null),
```

If you were not using the `wysiwyg` component, there is nothing else for you to do.

If you are using it, you will need to create an account on https://www.tiny.cloud/ ,
get an API key and add your application domains.

After obtaining your API key, add it to your `.env` file:
```
TINY_MCE_API_KEY=my-api-key
```

## 3.7.1

### front-end

#### calendar
- fixed request filter date format

#### filters
- fixes `fill` method for filter state
- limits width for picker inputs in date filter

#### tables
- improves global button actions handling

#### ui
- small changes to the AppUpdate messages; removed the version check when updating
- missing icon import fix

### back-end

#### companies
- added `Company::owner()` static helper method which returns the application owner company, based on the configured id (`config('enso.config.ownerCompanyId')` - more information below

#### core
- changed the AppUpdated notification tooltip message
- added `ownerCompanyId` key in `config.php`
- updated the `config.php` configuration, so that the values that make sense are read from the `.env` file if they provided
- cleanup of some deprecated image files

#### currencies
- fixes exchange fetcher service

#### forms
- fixed setting the route params for create
- added support for `pivotParams` & `customParams` meta properties for select

#### helpers
- fixed `filled` getter on `Obj` when value is an instance of `Collection`

#### localisation
- updated/added a few translation keys and values

#### menus
- marked the computed icon not exportable in table thus fixing a table export issue

#### roles
- fixes role table to show users without default menu
- fixes config writer for users without role

### deprecated

#### examples
- the package is being deprecated and will no longer be maintained
- for new projects, the relevant functionality (sample data for the dashboard charts) has been moved locally within Enso
- for existing projects that may still use the package, they may continue using it 

### Upgrade steps

This is a non breaking upgrade:
- run `composer update`
- run `yarn upgrade && yarn` in `/client`

### Company::owner()

The newly added method addresses the need to identify the Application's client or owner. Internally, we've handled this in various ways and, with this small update, we mean to provide an unified, consistent approach.

Since it makes sense that the Company model from the Companies package may be extended either in other packages or locally, the method's resulted class is resolved from the container.

Thus, if required, you can bind your desired/local implementation to the `LaravelEnso\Companies\app\Models\Company` class in the service provider.

You may set the application owner's company id within the main Enso configuration file (`enso.config.ownerCompanyId`).


## 3.7.0

The major purpose of this release is three fold:
* the switch to VueCli 4 which allows using the latest official VueJS tooling and should be more future-proof
* the date handling flow was simplified: we now expect that the front-end will send the date in the database's date format (usually `Y-m-d` or `Y-m-d H:i:s`) and on the back-end, all dates should be cast to Carbon\Carbon.
* the back-end packages were updated to require PHP 7.3+

### front-end

#### accessories
- default value for Documents.vue's disableUpload was fixed causing users to not be able to upload files

#### calendar
- added Event recurrency support
- small fixes and under the hood refactor
- import of VueCal language packs for the Enso supported languages which fixed translation issue

#### companies
- fixed small bug when assigning a person to the company, which caused the person to not be listed as assigned

#### confirmation
- fixed small styling issue with the control's position

#### currencies
- added filters on the index page
- for the currency display, symbols are used instead of icons 

#### datepicker
- default date format was updated from `'d-m-Y'` to `'Y-m-d'`
- `alt-format` & `alt-input` parameter usage was fixed

#### filters
- DateFilter now has a `direction` parameter & control: you can now choose to filter from past & future presets
- DateFilter also has a new `forward` boolean parameter which makes the component to start by default with the 
future presets
- under the hood date format handling has been improved

#### forms
- fixed the `alt-input` & `alt-format` handling for the date field
- instead of a single `actions` slot, the form now has a two `actions-left` & `actions-right` slots. This is a breaking change - see the upgrade steps

#### products
- various small css fixes

#### select
- making another request when the last request is still in progress will trigger the existing request's cancellation
- fixed bug when using the select in Object mode
- improved serverSide checking strategy
- the CoreSelect's `value` parameter is now required

#### tables
- fixed issue with unneeded scrollbars appearing under some circumstances
- small cleanup

#### themes
- theme files were renamed due to switching to VueCli and using the style loader webpack plugin
- Bulma version was bumped to ^0.8.0
- button sizes are customized to appear smaller than the new Bulma default (and slightly smaller than the old default)

#### ui
- the media resources that belonged to the UI (such as notification image SVGs, some logos) were moved to the ui package and are copied on build
- a new component is available in the navbar and being used to notify the currently
logged in users whenever a new application update has been deployed
- small changes made due to VueCli upgrade (routes, styles, themes)
- other small fixes

### back-end

#### activity-log
- updated date parsing due to filters changes

#### avatar
- added support for gif files

#### calendar
- recurring events are now supported
- various bugs were fixed
- updated the tests

#### companies
- updated message when setting/updating an assigned person/mandatary

#### countries (new)
- functionality derives from 'addresses` which now is now better encapsulated

#### core
- some of the resources used during the VueCli build process have been moved to this package, namely:
    - stubs for the index page
    - images used in the emails
- a new command (`php artisan enso:announce-app-update`)and logic has been implemented that can be used to notify currently logged in users that a new application version has been deplyed
- the older, deprecated upgrade commands where purged
- improved local state handling (breaking change)
- added upgrade commands for calendar, countries & tutorials
- the themes' configuration was updated

#### currencies
- created a Converter service which can convert from one currency to another, at a given date
- refactored fixer currency API
- fixed exchange rate validation and more
- fixed config API link
- updated date parsing due to datepicker changes
- added seeders for 17 currencies
- added `exchange_rates` date index
- various under the hood improvements
- updated the tests

#### enums
- updated PHP dependency to 7.3+

#### excel
- updated PHP dependency to 7.3+

#### files
- updated date parsing as required for latest filters usage
- fixed invalidMimeType exception message

#### forms
- updated date parsing for latest datepicker usage
- updated the config: the previous `dateFormat` parameter is now named `altDateFormat` (breaking change)
- fixed route generation in Builder
- updated the tests

#### helpers
- the `DateAttributes` trait was removed as a consequence of the new date handling flow (breaking change)

#### people
- updated date parsing as required for latest filters usage

#### permissions
- added menu related check when deleting permission

#### rememberable
- fixed caching bug when choosing to cache 'forever'

#### tables
- updated date filtering strategy which resulted in much simpler and robust code
- fixed default sort issue
- fixed rawTotal computation issue for empty tables
- fixed bug when exporting nested columns

#### tutorials
- the `show` route was renamed to `load`
- fixed small form bug

#### versions
- updated PHP dependency to 7.3+

### Upgrade steps

Before getting into the actual upgrade steps, an overview of the changes is useful.

When switching from Laravel Mix to VueCli a few things happen:
- the front-end resources location will change from `resources/js` to `client/src/js`
- the front-end dependencies location will change from `node_modules` to `client/node_modules`
- the `package.json` configuration file location will change from project root to `client`
- the front-end patches location will change from `patches` to `client/patches`
- when installing dependencies using Yarn, building or serving the front-end, you will need to `cd` into the client folder first.
- instead of using Laravel Mix's `webpack.mix.js` file to configure the build, we will be using VueCli's `vue.config.js`
- we can still use Valet to serve the back-end but we must use `yarn serve` to serve the front-end for
hot module replacement

#### VueCli upgrade related steps
- copy the `client` folder from the Enso repo into your project root
- add any (package) aliases you had previously added to the `webpack.mix.js` file into: 
    - the `settings.import-resolve.alias.map` section of the `client/.eslintrc.js` file
    - the `configureWebpack.resolve.alias` section of the `client/vue.config.js` file
- update/replace your project root `.eslintrc.js` with the contents from `client/.eslintrc.js` and correct the aliases paths
- if you had customized the `.babelrc` file, move those customizations to the `client/.babelrc` file, and remove the root file
- move your patches from `patches` to `client/patches`
- move any local dependencies you had added to the `package.json` to the `client/package.json` file
- move any additions from the `resources/js/app.js` file to the `client/src/js/app.js`
- move any additions from the `resources/js/localState.js ` file to the `client/src/js/localState.js `
- move any additions from the `resources/js/router.js ` file to the `client/src/js/router.js `
- move your local components, pages, routes & store from `resources/js` to `client/src/js`
- once you have moved your js files, you may delete the `resources/js` folder
- if you have any local image resources in `resources/images` you can create a `client/src/images` folder and move them there. Afterwards, you should add a configuration object to the CopyPlugin section from `vue.config.js` so that the images are copied on build.

    You may then delete the `resources/images` folder (the image files provided with Enso, such as the ones in the `emails` and `corners` have been extracted to their respective packages)
- move any additions from within the `resources/sass` files to the `client/src/sass` files
- once you've moved everything, the `resources` folder should look like on the Enso repo [here](https://github.com/laravel-enso/enso/tree/master/resources)

- remove the `webpack.mix.js` file
- update `.gitignore` using Enso's as example and double check `client/.gitignore`
- within `composer.json` update:
    - `laravel-enso/core` to `4.6.*`
    - `laravel-enso/calendar` to `1.4.*` (if using the calendar package)
- run `composer update` in the project root to update the back-end packages
- cd into the `client` folder and run `yarn` to install the front-end packages
- make a copy of the `client/.env.example` file, name it `.env`, then edit it an set the url you're currently using with Valet, for your project
- update the themes' configuration (`config/enso/themes`) to:
```php
return [
    'light' => 'light',
    'dark' => 'dark',
    'light-rtl' => 'light-rtl',
    'dark-rtl' => 'dark-rtl',
];
```

#### Enso upgrade related steps:
- search for any `DateAttributes` usages, remove the import and any date setters you might have added
- when refactoring date attributes, you may use this [commit](https://github.com/laravel-enso/people/commit/15ea16132957e3ce1bea9b1066fdb3e4f079e79e) as an example
- search and replace the `LaravelEnso\Addresses\app\Models\Country;` namespace with `LaravelEnso\Countries\app\Models\Country;`
- where using the `actions` slot within forms, replace the `actions` slot with `actions-left` and/or `actions-right` slots as required
- the local state implementation class should be bound in the service container to the `LaravelEnso\Core\app\Services\LocalState`
- publish the updated `AddressFactory.php`: 
```
php artisan vendor:publish --tag=addresses-factory --force
```
- publish the updated `CountrySeeder.php`
```
php artisan vendor:publish --tag=countries-seeder --force
```
- if using the Calendar, also:
    - publish the calendar factories
    ```
    php artisan vendor:publish --tag=calendar-factories --force
    ```
    - add the calendar test suite to `phpunit.xml`:
    ```xml
        <testsuite name="calendar">
            <directory suffix="Test.php">./vendor/laravel-enso/calendar/tests</directory>
        </testsuite>
    ```
    - add the following namespace `"LaravelEnso\\Calendar\\tests\\features\\": "vendor/laravel-enso/calendar/tests/features/",` to `composer.json`'s `autoload-dev.psr-4`
- in `config/enso/config.php` increment the version to 3.7.0
- in `config/enso/forms.php` update the `dateFormat` key to `altDateFormat`
- cd into the `client` folder and serve the front-end with `yarn serve` or build it with `yarn build`
- (optional) a new feature allows you to notify your currently logged in users that a new application update/version was deployed.
 
    To use it, update your live deployment process and after the deploy is done, add a hook that calls `php artisan enso:announce-app-update`.
    The logged in users will get a toaster message as a well a pulsing notification control in the navbar, which, when clicked, will reload the page.
    
    Note that for the user to be notified, the current in-page version must be different from the released version, so don't forget to update the `enso.config.version` value.

    Reloading helps avoid js errors related to the lazy loading of the front-end assets as well as any mismatches between the stale front-end and the updated back-end API.

    The page reload is not done automatically for a better user experience as well as to give users the chance to save their work.

#### Serving the assets

Serving the assets has changed from running `yarn run hot` to cd-ing into the 
`client` folder and running `yarn serve`.

No other steps are necessary if you are serving the back-end API as before, for example with Valet.

Alternatively, if you don't want to use Valet, you may run `php artisan serve` and then point to the back-end URL, within the `client/.env` config.

#### Building the assets

Building the assets has changed from running `yarn run prod` to cd-ing into the 
`client` folder and running `yarn build`.

While the command is different, the resulted build is, for all practical purposes, identical to the one resulted when using Laravel Mix.

#### Deploying in production 

To deploy the build you should follow the same flow as before. 

IF within your workflow you are also building the front-end, you should update your scripts so as to take into the account the `client` folder (see above).

## 3.6.1

### front-end

#### activity log
- keepAlive was removed form the index page

#### calendar
- as the package is no longer included in the UI package, if you want to continue using it, it should be manually installed
- previously, the calendar was more of a stub package with little functionality, now it has been rewritten and updated, with features such as:
    - creating and managing local calendars

#### emails
- is a new package that can be used to compose emails for bulk sending (within the application)

#### files
- keepAlive was removed form the index page

#### filters
- fixed a bug in for the interval filters' computed value

#### products
- small cleanup

#### switch
- fixed readonly & disabled state

#### tables
- the 'Totals' footer is now collapsible, similar to the other columns

#### typeahead
- css styling was updated thus fixing some scrollbar issues and sub components sizing

#### ui
- various small changes

### back-end

#### activity log
- small refactor
- the Events list now has better, user friendly labels

#### addresses
- the `Country` model now uses Rememberable (caching)
- the Addresses factory should be force published as it has been updated - this now fixes
the tests which appeared to randomly fail

#### avatars
- the uploaded avatar now has the `created_by` attribute properly set

#### cli
- fixed page tiles for create, edit, show routes

#### companies
- the `Active` status is now selected by default in the form when creating a new company

#### control panel
- date handling was refactored

#### control panel api
- refactored the statistics building flow

#### core
- the `Calendar` package was removed as an implicit dependency - if required add it manually using composer
- fixed the User seeder
- generates now the broadcast private channel name dynamically, suited for cases where the user is under a custom namespace

#### currencies (new)
- is a package that can be used to manage currencies and exchange rates
- various small changes and improvements
- improved request validation
- added [Fixer Currency](https://fixer.io) API integration so the current exchange rates can be automatically retrieved for your defined currencies

#### cli
- page titles for edit, create, show where fixed
- various small changes and improvements

#### data export
- the authenticated user is now passed in the `ExcelExport` service so that the `created_by` flag can be accurately set for the generated files
- the computation for the sheet limit has been fixed

#### data import
- fixes setting the `created_by` attribute for the rejected file

#### emails (new)
- various small changes

#### files
- the `File` model's `between` scope now includes the limits of the given interval whereas in the past they where excluded
- the `Files` service's  `attach` method should also receive a `$user` model so we can properly set
the `created_by` attribute on the model
- `FilePolicy`'s `ownsFile` method visibility what changed from private to protected so it can be overwritten
if required

#### forms
- updated tests to use the configured user model

#### helpers
- updated the `InCents` trait to handle `Pivot` models
- fixed the event name in the `ActiveState` trait (typo)
- enhanced the date parsing strategy for the `DateAttributes` trait in that if the date cannot be parsed
using the available format(s), `Carbon::parse` will be used as fallback

#### notifications
- generates now the broadcast private channel name dynamically, suited for cases where the user is under a custom namespace

#### products
- the product-supplier relationship has been enhanced with the use of Pivot model class for the pivot
- the supplier prices are now saved 'in cents' through the use of the `InCents` trait

#### rememberable
- the package has been rewritten for better code performance, readability & maintainability
- in addition to the existing Cache mechanism, a new Volatile transparent layer is used by default, which uses an in-memory cache (which is available during the request).

#### select
- a new `TypeaheadBuilder` trait has been added to the package, which acts like a shim between the typeahead front-end and the Options backend so we can have select-like ease of use when using typeaheads

#### tables
- various small changes, bug fixes and improvements
- a `DynamicTemplate` contract & mechanisms  are now available that address a possible security issue when caching templates and having different templates and data for different users
- enhanced interval filter handling
- updated tests to use the configured user model
- the exported file now has the `created_by` attribute properly set

#### teams
- removed deprecated loggable properties from the model

### Upgrade steps

- `composer update`
- `yarn upgrade && yarn`
- the Addresses factory should be force published (pay attention if you've customized it):
`php artisan vendor:publish --tag=addresses-factory --force`
- update your User seeder or force publish the Core seeders (pay attention if you've customized them):
`php artisan vendor:publish --tag=core-seeders --force`
- update the Enso version within your config file to reflect the 3.6.1 version
- if you want to install the calendar package:
    - `composer require laravel-enso/calendar:1.3.x`, then add
    - `yarn add @enso-ui/calendar@1.2.x`
    - read [docs](https://docs.laravel-enso.com/backend/calendar.html#installation)
- `php artisan enso:upgrade`
- `php artisan migrate`

For the next release we aim to move to Vue Cli from using Laravel mix.

## 3.6.0

### front-end

#### accessories
- adds a `canAccess`a check in documents to avoid an error
- fixes the uploader import in documents

#### activity-log
- major refactor for the new BE version, backward incompatible.

#### charts
- adds `Chart` to exports

#### datepicker
- adds support for `altInput` & `altFormat`

#### filters
- updates `date-filter` interval logic. Previously, "today" generated an interval between today@00:00:00 (AM) and tomorrow@00:00:00 (AM). Now the interval is between today@00:00:00 and today@23:59:59. The same principle applies for the other intervals.
- all the filters had the `title` property renamed to `name` (was causing unwanted native tooltip)

#### select
- adds `axios` request cancelling on concurrent requests
- fixes a bug on handling the value when operating in `objects` mode
- improves serverSide check strategy
- fixes bug where an error was thrown when using w/o server side

#### tables
- refactored for the new v3.0.0 BE

#### switch
- fixes disable and readonly states

### back-end

#### activity-log
- rebuilt from scratch
- registration of events is made in `LoggerServiceProvider'
- all registered / logged events use Laravel native Model events and Observers
- allows any number of custom events to be registered with models by extending the existing structures
- now the messages are properly translated
- for multi part events messages can be strings or arrays, (helps localisation)
- added a default `UpdatedActiveState` event
- documentation will be updated soon
- adds tests

#### addresses
- fixes test that was sometimes causing problems
- adds a `label()` helper to the `Address` model
- provides address `label` customization by config

#### companies
- adds unique index for `fiscal_code` and `reg_com_nr`
- consolidates the two request validators for `Company` in one `ValidateCompanyRequest`
- upgrades the table for v3.0.0
- when creating a company the active status is preselected
- fax and bank were removed from the index table

#### core
- consolidates the two request validators for `UserGroup` in one `ValidateUserGroupReques`
- upgrades the table for v3.0.0
- adds the new `dateTimeFormat` in config and in the `AppState` builder
- adds the `Activatable` contract on the `User` model

#### data-import
- adds an `Authenticates` contract that can be used by importer classes when the user that performs the import needs to be authenticated
- fixes a bug occuring when generating the rejected file, which caused some validation errors to not be reported

#### excel (new)
- provides an `ExportsExcel` contract and the `ExcelExport` service that makes excel exports an easy task
- package was added to the base Enso project

### pdf
- package was added to the base Enso project

#### forms
- adds meta support for select's `objects` property
- fixes a bug when manually set values were transformed by the builder for the selects operating in 'object' mode
- improves `needsValidation()` logic

#### helpers
- adds the `Activatable` contract
- changes the `ActiveState` trait by renaming:
    - getter `isDisabled()` => `isInactive()`
    - helper `disable()` => `deactivate()`
    - scope `disabled()` => `inactive()`
- fixes an edge case bug on `InCents`

#### files
- fixes `FilePolicy`

#### localisation
- consolidates the two request validators for `Language` in one `ValidateLanguageRequest`
- upgrades the table for v3.0.0
- adds the `Activatable` contract on `Language` model

#### menus
- consolidates the two request validators for `Menu` in one `ValidateMenuRequest`
- upgrades the table for v3.0.0

#### people
- consolidates the two request validators for `Person` in one `ValidatePersonRequest`
- upgrades the table for v3.0.0

#### permissions
- consolidates the two request validators for `Permission` in one `ValidatePermissionRequest`
- upgrades the table for v3.0.0

#### roles
- consolidates the two request validators for `Role` in one `ValidateRoleRequest`
- upgrades the table for v3.0.0

#### tables
- the package was completely refactored
- the table builder class now has to implement a `Table` contract instead of extending a service class
- adds a series of contracts besides `Table`:
    - `AuthenticatesOnExport` => should be used for tables where the logged user is needed for restricting visible rows or any other purpose. The exporter will make sure that the user who requested the export will be authenticated before any queries are performed
    - `CustomFilter` => should be used for tables when custom filter logic is needed via `params`. Will make sure that the correct count is displayed when custom filters are applied
    - `RawTotal` => should be used with tables where the total cannot be computed simply by adding up column values (e.g. `sum(price * quantity))
    - `Filter` => can be used to register local filters
    - `ComputesColumns` => can be used to register local computors
- improves exceptions
- adds template caching, configurable by global config or per table. Comes with a `php artisan enso:tables:clear` command that should be used on production when opting for cacheing templates
- renamed `cache` to `countCache`. Can now be set globally in the config. The builder will notify the dev if certain models are missing the `TableCache` trait
- makes the `buttons` attribute mandatory in templates
- date interval filters object must now contain the `dateFormat` prop representing the FE format of the date. If the dateFormat prop is null, the `config('enso.config.dateFormat')` will be used
- `dbDateFormat` was removed
- improves the strategy for auto-detecting the `model` property. The camel case of the model class short name is now used 
- determines the table's `name` property (if not provided in the template) by pluralising the `model` property (see above)
- the notification channels should be present in config `['mail', 'broadcast', 'database']
- all tests were refined

#### tutorials
- consolidates the two request validators for tutorial in one `ValidateTutorialRequest`
- upgrades the table for v3.0.0
- adds `TableCache` on the `Tutorial` model

### Upgrade Steps

- update `composer.json` dependencies to:
    - "laravel-enso/cli": "3.3.*"
    - "laravel-enso/core": "4.5.*"
    - "laravel-enso/pdf": "1.0.*"
    - "laravel-enso/excel": "1.0.*"
- add in `composer.json` => `autoload-dev.psr-4` section:
    - "LaravelEnso\\Cli\\tests\\": "vendor/laravel-enso/cli/tests/"
    - "LaravelEnso\\Tables\\Tests\\": "vendor/laravel-enso/tables/tests/"
- update `package.json` dependencies to:
    - "@enso-ui/accessories": "2.1.x"
    - "@enso-ui/bulma": "2.1.x"
    - "@enso-ui/directives": "1.0.x"
    - "@enso-ui/mixins": "1.0.x"
    - "@enso-ui/tables": "1.2.x"
    - "@enso-ui/themes": "1.0.x"
    - "@enso-ui/transitions": "1.0.x"
    - "@enso-ui/ui": "2.1.x"

- remove from `package.json`
    - "@enso-ui/filters": "^1.1.1"
    - "@enso-ui/forms": "~1.2.0"
    - "@enso-ui/select": "~1.1.1"

- run `composer update && yarn && yarn upgrade && yarn"
- run `php artisan enso:upgrade`

- add in phpunit.xml the test suite for activity-log
```xml
<testsuite name="activity-log">
    <directory suffix="Test.php">./vendor/laravel-enso/activity-log/tests/units</directory>
</testsuite>
```
- add in `config/enso/config.php` the `dateTimeFormat` key

#### activity-log
- truncate the `activity_logs` table. Or if required, you can manually migrate the old data to the new format
- publish the `LoggerServiceProvider` and configure it using the provider from this repo as an example: `php artisan vendor:publish --tag=activity-log-provider`
- update the UserSeeder ( if necessary ) so that it is compatible with the new `ActivityLog` structure

#### addresses
- add the to the addresses config file the `label` key

#### data-import
- add the new `Authenticates` contract to all imports that need the authenticated user

#### filters
- replace the `title` prop with `name` for all the usage instances of the following filters: 
    - `BooleanFilter`, 
    - `CoreSelectFilter`, 
    - `EnsoFilter`,
    - `EnsoDateFilter`,
    - `DateFilter`, 
    - `DateIntervalFilter`, 
    - `EnsoSelectFilter`, 
    - `IntervalFilter`, 
    - `SelectFilter` 
    - `VueFilter` . 

    To do this you can search the whole resources folder for `title="`. Make sure you only replace the prop for filters and not other components
- make sure that the change in `date-filter` (described above at the front-end > filters section) does not impact your local use cases
- whenever using date-filters with tables, remove `dbDateFormat` and make sure `dateFormat` is present in the interval object, so the back-end knows it's a date interval. `dateFormat` should reflect the ui date format. If left null, the backend will consider `config('enso.config.dateFormat')`.

#### helpers
- make sure that you replace `isDisabled()` & `disable()` helpers usage with `isInactive()` & `deactivate()` and `disabled()` scope with `inactive()`

#### request validators
- most of the `ValidateModelStore` / `ValidateModelUpdate` request validators were consolidated into one `ValidateModelRequest` (see the changelog). Check all local classes that are extending Enso request validators and make sure they extend the right class.

#### tables
- remove the usage of the `Datatable` trait as it no longer exists. You may search for `use LaravelEnso\Tables\app\Traits\Datatable;` and remove any traces;
- refactor all the table builders to implement the new `Table` contract and expose the required `query()` and `templatePath()` methods. You can follow as example the refactor of the [permissions table](https://github.com/laravel-enso/permissions/commit/707255e3a95a6e8fd87b581db689dfb623449ed5#diff-04ef87d5f506cd5a1ed3d19c5fe0c96b)
- the `protected $templatePath` property has become `protected const TemplatePath`;
- if you have extended any of the core tables in your local project make sure to take into account the changes
- add in the tables.php config file the cache key and update (if required) the notifications value' => `['email', 'broadcast', 'database']`
- add in the tables.php config file under the export key => ``sheetLimit => 1000000``
- where used in templates, rename `count` with `countCache` or set it to `true` globally in the config
- where missing, add the `buttons` property in all templates  (empty array)
- see the above `filters` mentions regarding `dbDateFormat`
- add the `AuthenticatesOnExport` contract on tableBuilders that need the authenticated user on exports

## 3.5.0

### front-end

We've created individual packages for resources that map on their back-end siblings for:
- calendar
- files
- data-import
- activity-log
- people
- companies
- teams
- roles
- permissions
- menus
- localisation
- tutorials
- how-to

This allows much easier customisation...

#### switch
- fixes not clickable bug on IOS

#### tables
- cleans up vResponsive
- fixes file download on default download button

### back-end

#### cli
- fixes menu creation w/o model bug
- updates table template stub
- updates form builder stub to use `static` vs `self`
- updates stubs for the new aliases in enso-ui 2.x

#### companies
- makes `status` required in form validator
- fixes person form in edit mode, the user field is now readonly

#### core
- adds an upgrade for the new data-import

#### data-import
- refactors, cleans & increases accuracy for the progress tracking strategy

#### forms
- exposes `sectionVisibility` & `tabVisiblity` helpers by making them public
- fixes `unknownMetaAttributes()` validation error reporting

#### helpers
- fixes inCents when having null cent attributes
- adds `CarsadesMorphMap` trait. You can use this in combination with Laravel's `Relation::morphMap()` and have a single alias for all children classes. The convention is to use the singular camel case name of the model's table

#### rememberable
- refactored `self` vs `static` in model retrieving

#### tables
- fixes `cents` flag appeared as true when no cent columns defined bug

### Upgrade Steps

- update in `package.json` the following
    - "@enso-ui/accessories": "~2.0.0",
    - "@enso-ui/bulma": "~2.0.0",
    - "@enso-ui/directives": "~1.0.1",
    - "@enso-ui/filters": "^1.1.1",
    - "@enso-ui/mixins": "~1.0.0",
    - "@enso-ui/select": "~1.1.1",
    - "@enso-ui/themes": "~1.0.0",
    - "@enso-ui/transitions": "~1.0.1",
    - "@enso-ui/ui": "~2.0.0",
- replace in `webpack.mix.js`

```js
'@core-routes': `${__dirname}/node_modules/@enso-ui/ui/src/bulma/routes`,
'@core-pages': `${__dirname}/node_modules/@enso-ui/ui/src/bulma/pages`,
'@core-middleware': `${__dirname}/node_modules/@enso-ui/ui/src/middleware`,
'@core-modules': `${__dirname}/node_modules/@enso-ui/ui/src/modules`,
```

with

```js
'@core': `${__dirname}/node_modules/@enso-ui/ui/src`,
```

- search and replace:
    - `@core-modules` => `@core/modules`
    - `@core-pages` => `@core/bulma/pages`
    - `@core-middleware` => `@core/middleware`
    - `@core-modules` => `@core/modules`
- update in `router.js`:
    - add `import routes from '@core/bulma/routes';`
    - remove `const routes = routeImporter(require.context('@core-routes', false, /.*\.js$/));`
- run `yarn upgrade`
- update your front-end patches if the case requires
- run `yarn` (to apply the patches)
- run `composer update`
- run `php artisan enso:upgrade`

Enjoy!

## 3.4.0

### front-end

#### accessories
- adds permission checks for various document operations

#### filters
- cascades enso select event handlers in select filter
- adds v-model to date filter, with value required
- adds a `title` prop to date-filter
- fixes tooltip in date-filter
- adds watcher for `value` prop in date-filter
- fixes horizontal padding in vue-filter

#### forms
- allows passing `params` in select fields
- avoids scrollIntoView erorrs (eg when using hidden fields)

#### select
- adds watcher for `source`
- no longer fetches on click when the component is readonly / disabled
- locks selection when params / pivot-params / custom-params are changed until the new list of options is fetched
- removes `disable-filtering` prop
- disables front end filtering when using server-side

#### tables
- improves vResponsive directive
- refactors dtRowId
- adds a `FilterState` helper component
- renames bulma `Controls` component names
- minor visual refactor

#### typeahead
- adds automatic route translation in enso-typeahead
- cascades clear

#### ui
- the `route` helper is no longer defined as global and must now be injected in every component
- fixes menu label padding
- improves tag group template in search, adds horizontal scrolling
- fixes checkbox alignment in checkbox manager
- fixes params in dataimport index template

### back-end

#### addresses
- removed superfluous validation
- removes address observer
- add laravel-enso/enums
- improves form extending, removes formTemplate from config
- adds lat and long fields in form, with custom validation rules
- renames DefaultAddress controller to SetDefault
- improves scopeFor to work with morphMap
- removes deprecated Addresses trait
- refactors exceptions

#### avatars
- removes redundant method call in default avatar
- upgrades laravolt/avatars to 3.0

#### calendars
- adds laravel-enso/enums
- adds enums service provider for registering calendars

#### charts
- small refactor

#### cli
- adds a package option. Now you can generate whole structure boilerplates directly in your `vendor/my-vendor/my-package` folder
- adds session saving. You can now configure a structure, exit (or encounter an error :P) then come back and continue from where you left
- adds unit tests
- adds laravel-enso/enums

#### comments
- removed superfluous validation
- small refactor

#### companies
- implements dependency injection in controllers
- adds rememberable, avoids deletion conflicts and relations traits to the company model
- adds laravel-enso/enums
- improves form for easier customization
- adds `fiscal_code`, `reg_com_nr` and `status` fields in table migration
- adds `CompanyStatuses` enum
- adds a `EnumServiceProvider`
- removes `companies.php` config file
- removes default restrictions in Options controller
- adds fiscal_code to $queryAttributes in Options
- refactors exceptions

#### core
- adds laravel-enso/enums
- adds `EnumServiceProvider`
- fixes email unique validation in user form validation
- removes duplicate trait use in UserGroup
- adds rememberable on UserGroup
- adds rememberable, avoids deletion conflicts and relations traits to User
- improves user and group form for easier customization
- removes versionings as a requirement
- updates `Upgrade` command for 3.4.0

#### data-export
- adds a chunk option in config for setting the default value
- updates policies: view, share, destroy
- upgrades box/spout to 3.0
- implements the new AuthorizesFileAccess

#### data-import
- small refactor
- updates contracts (beforehook, importable, afterhook) to receive the authenticated user as well
- adds laravel-enso/enums
- implements AuthorizesFileAccess contract
- updates policies: view, share, destroy
- upgrades box/spout to 3.0
- refactors exceptions

#### discussions
- small refactor

#### documents
- removed superfluous validation
- updates policies: view, share, destroy
- implements AuthorizesFileAccess

#### dynamic-methods (new)
- a set of traits that allow adding methods / relations / mutators / accessors in models from a local service provider

#### enums (new)
- Enso Enum was moved from laravel-enso/helpers in itw own package
- has an EnumServiceProvider that allows registering enums that are sent by the app state builder to the front-end. The provider can be extended in any package or locally

#### files
- greatly improves the authorizan strategy
- adds AuthorizesFileAccess contract
- adds an UploadPolicy for view, share and destroy
- refactors exceptions

#### forms
- removes deprecated `allow-filtering` option from meta
- adds `params` to meta for select fields
- allows use of nested attributes
- refactors exceptions

#### helpers
- adds an optional precision for all methods in Decimals
- adds floor and ceil to Decimals
- fixes an edge case bug in Obj's filled method
- adds DatabaseSeederProgress and SeederProgress traits that allow displaying a progress bar in the seed process
- updates InCents to do floor on converting in cents
- removes Enum (now lies in its own package)

#### how-to
- small general refactor
- refactors exceptions

#### image-transformer
- refactors exceptions

#### io
- adds laravel-enso/enums

#### localisation
- refactors the `localisation` key to `language`
- renamed form & table (builders & templates)
- improves form for easier customization
- refactors exceptions

#### logs
- refactors exceptions

#### menus
- small refactor
- improves form for easier customization

#### notifications
- removes unused vars in notification

#### people
- improves form for easier customization
- adds AvoidsDeletionConflicts, Relations, Rememberable on Person
- adds laravel-enso/enums
- adds EnumServiceProvider
- adds bank and bank_account to the table's migration
- adds uid to $queryAttributes in Option
- removes people.php config file

#### permissions
- small refactor
- improves form for easier customization

#### rememberable
- cacheGet($id) now return null if the model does not exist in the database

#### roles
- adds rememberable on Role
- adds laravel-enso/enums
- improves form for easier customization
- refactors exceptions

#### searchable
- casts labels sent to the front-end as strings
- refactors Finder.php
- improves the logic in Search.php

#### select
- refactors the option builder

#### tables
- small refactor & cleanup of redundant logic
- refactores filters
- dtRowId is now configurable and defaults to "id"
- the box/spout dependency was upgraded to the v3 version
- adds/uses `laravel-enso/enums`
- now uses the Laravel 6 Arr class instead of the global helpers
- adds `model` prop in template for customizing the model
- refactors exceptions

#### track-who
- improves resources logic
- refactors auth helper into facade
- fixes updatedByTest

#### teams
- team now uses rememberable

#### tutorials
- small refactor
- improvements to form customization
- adds/uses `laravel-enso/enums`

#### versions (new)
- alternative package to versioning where the version is kept on the model vs using a separate table

#### versioning
- improves flow, optimizes queries, fixes N+1 problem
- addresses scenario where N+1 queries were being made by using $with
- renames versioning table to precede all other model migrations

### Upgrade steps

#### Local project

- update in composer.json:
    - require
        - "laravel-enso/control-panel-api": "2.3.*", // optional, if you are using it
        - "laravel-enso/core": "4.4.*",
        - "laravel-enso/enums" "1.1.*",
    - require-dev
        - "codedungeon/phpunit-result-printer": "^0.26.0",
        - "facade/ignition": "^1.4",
        - "nunomaduro/collision": "^3.0",
        - "phpunit/phpunit": "^8.0"
- update in package.json
    - "@enso-ui/accessories": "~1.1.0",
    - "@enso-ui/bulma": "~1.2.0",
    - "@enso-ui/forms": "~1.2.0",
    - "@enso-ui/select": "~1.1.0",
    - "@enso-ui/tables": "~1.1.0",
    - "@enso-ui/ui": "~1.5.0",
- rename `structuremanager` test suite to `cli` in phpunit.xml
- remove from the local `User` model the `Addresses` & `Documents` traits
- update the `CompanyFactory` with the one from the github repo (of course, retain any local additions if necessary)
- update `enso/exports.php`, `enso/tables.php`, `laravolt/avatar.php` config files with the ones from the github repo
- delete the `enso/people.php` & `enso/companies.php` deprecated configs
- make sure to refactor any customisation to the people / companies forms or tables using dependency injection / binding in your `AppServiceProvider`. The old customization mechanisms which were using configs have been removed.
- search and replace `LaravelEnso\Helpers\app\Classes\Enum` => `LaravelEnso\Enums\app\Services\Enum`
- either refactor all table builders and remove the `dtRowId` alias for the id, or update your local table templates and add `"dtRowId": "dtRowId"`
- make sure you inject `route` in all the VueJS components where the helper is used. Search for "route()" in your resources folder and refactor
- refactor any customizations to documents, uploads, imports, exports to make use of the `AuthorizesFileAccess` contract
- update all importers and classes implementing the `BeforeHook`, `Importable` and `AfterHook` contracts, as the `before`, `run` and `after` method signatures have changed
and a and `$user` parameter was added
- add a `value` parameter to all `date-filters` or `enso-date-filters` component use
- if you are currently using the [Versioning](https://github.com/laravel-enso/versioning) package, manually add it as a dependency in your composer.json under `require`, as it was removed as a dependency from [Core](https://github.com/laravel-enso/core)
- run `php artisan enso:upgrade`
​
Optional:
- now you can register/add Enums directly from your packages, to the application state, by using an `EnumServiceProvider`. 
Take a look at the `EnumServiceProvider` from the [People](https://github.com/laravel-enso/people) package as an example.
If using this method, you won't need to add them in the project's `LocalState`.#### Local project

- update in composer.json:
    - require
        - "laravel-enso/control-panel-api": "2.3.*", // optional, if you are using it
        - "laravel-enso/core": "4.4.*",
        - "laravel-enso/enums" "1.1.*",
    - require-dev
        - "codedungeon/phpunit-result-printer": "^0.26.0",
        - "facade/ignition": "^1.4",
        - "nunomaduro/collision": "^3.0",
        - "phpunit/phpunit": "^8.0"
- update in package.json
    - "@enso-ui/accessories": "~1.1.0",
    - "@enso-ui/bulma": "~1.2.0",
    - "@enso-ui/forms": "~1.2.0",
    - "@enso-ui/select": "~1.1.0",
    - "@enso-ui/tables": "~1.1.0",
    - "@enso-ui/ui": "~1.5.0",
- rename `structuremanager` test suite to `cli` in phpunit.xml
- remove from the local `User` model the `Addresses` & `Documents` traits
- update the `CompanyFactory` with the one from the github repo (of course, retain any local additions if necessary)
- update `enso/exports.php`, `enso/tables.php`, `laravolt/avatar.php` config files with the ones from the github repo
- delete the `enso/people.php` & `enso/companies.php` deprecated configs
- make sure to refactor any customisation to the people / companies forms or tables using dependency injection / binding in your `AppServiceProvider`. The old customization mechanisms which were using configs have been removed.
- search and replace `LaravelEnso\Helpers\app\Classes\Enum` => `LaravelEnso\Enums\app\Services\Enum`
- either refactor all table builders and remove the `dtRowId` alias for the id, or update your local table templates and add `"dtRowId": "dtRowId"`
- make sure you inject `route` in all the VueJS components where the helper is used. Search for "route()" in your resources folder and refactor
- refactor any customizations to documents, uploads, imports, exports to make use of the `AuthorizesFileAccess` contract
- update all importers and classes implementing the `BeforeHook`, `Importable` and `AfterHook` contracts, as the `before`, `run` and `after` method signatures have changed
and a and `$user` parameter was added
- add a `value` parameter to all `date-filters` or `enso-date-filters` component use
- if you are currently using the [Versioning](https://github.com/laravel-enso/versioning) package, manually add it as a dependency in your composer.json under `require`, as it was removed as a dependency from [Core](https://github.com/laravel-enso/core)
- run `php artisan enso:upgrade`
​
Optional:
- now you can register/add Enums directly from your packages, to the application state, by using an `EnumServiceProvider`. 
Take a look at the `EnumServiceProvider` from the [People](https://github.com/laravel-enso/people) package as an example.
If using this method, you won't need to add them in the project's `LocalState`. 
​
#### Laravel 6 file changes
- `bootstrap/app.php`:
    - update the `$app` variable value
    ```
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
    ```
    - be sure to clear the cache (? `php artisan cache:clear` )
​
- `configs/app.php`:
    - add `'asset_url' => env('ASSET_URL', null),`
    - add `'faker_locale' => 'en_US',`
    - add the following aliases to the `aliases` array
    ```
    'Arr' => Illuminate\Support\Arr::class,
    'Str' => Illuminate\Support\Str::class,
    ```
​
- `configs/auth.php`:
    - add the following value to `guards.api` configuration array: `'hash' => false,`
​
- `configs/broadcasting.php`
    - replace `'encrypted' => true,` with `'useTLS' => true,` in the `connections.pusher.options` configuration array
​
- `configs/cache.php`
    - add the dynamodb configuration to the `stores` configuration array
    ```
    'dynamodb' => [
        'driver' => 'dynamodb',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
        'endpoint' => env('DYNAMODB_ENDPOINT'),
    ],
    ```
    - update the `prefix` value with `env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),`; Remember to import `Str` (Illuminate\Support\Str) class.
​
- `configs/database.php`
    - add the following values to the `connections.sqlite` configuration array:
    ```
    'url' => env('DATABASE_URL'),
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    ```
    - add the following values to the `connections.mysql` configuration array:
    ```
    'url' => env('DATABASE_URL'),
    'prefix_indexes' => true,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
    ```
    - add the following values to the `connections.pgsql` configuration array:
    ```
    'url' => env('DATABASE_URL'),
    'prefix_indexes' => true,
    ```
    - add the following values to the `connections.sqlsrv` configuration array:
    ```
    'url' => env('DATABASE_URL'),
    'prefix_indexes' => true,
    ```
    - the `redis` configuration array should be updated:
    ```
    'redis' => [
        'client' => env('REDIS_CLIENT', 'predis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],
    ```
- `configs/filesystems.php`
    - the following key values should be updated for the  `disks.s3` configuration array:
    ```
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    ```
       Note that the AWS configuration keys have changed and should be updated within the env file as well.
​
- `configs/logging.php`
    - for the `channels.stack` configuration array:
        - add the following value `'ignore_exceptions' => false,`
        - update `channels` to `'channels' => ['single'],` // or 'daily'
    - update the `channels.daily` value to `'days' => 14,`
    - add the new `papertrail` value to the `channels` configuration array:
    ```
    'papertrail' => [
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => SyslogUdpHandler::class,
        'handler_with' => [
            'host' => env('PAPERTRAIL_URL'),
            'port' => env('PAPERTRAIL_PORT'),
        ],
    ],
    ```
    - add the new `formatter` value to the `channels.stderr` configuration array: `'formatter' => env('LOG_STDERR_FORMATTER'),`
​
- `configs/mail.php`
    - add the following value: `'log_channel' => env('MAIL_LOG_CHANNEL'),`
​
- `configs/queue.php`
    - add the following value to the `connections.beanstalkd` configuration array: `'block_for' => 0,`
    - add the following values to the `connections.sqs` configuration array:
    ```
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
    'queue' => env('SQS_QUEUE', 'your-queue-name'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ```
- `configs/services.php`
    - add the following value to the `mailgun` configuration array:: `'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),`
    - add the `postmark` configuration:
    ```
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    ```
    - update the `ses` configuration:
    ```
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    ```
    - remove the `stripe` and `sparkpost` configurations
- `configs/session.php`
    - update the `cookie` configuration array:
    ```
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    ```
    Be sure to also import the `Str` class.
- `configs/view.php`
    - update the `compiled` configuration array:
    ```
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
    ```
​
- `resources/lang/en/validation.php`
    The following translation keys and their values have to be added:
    - 'date_equals'
    - 'ends_with'
    - 'gt'
    - 'gte'
    - 'lt'
    - 'lte'
    - 'not_regex'
    - 'starts_wit'
    - 'uuid'
- `tests/Bootstrap.php` file was added.​
- `app/Http/Kernel.php`
   - the `ThrottleRequests` middileware was added to the `$middlewarePriority` variable.
- `.env`
    - add if necessary `MAILGUN_ENDPOINT=api.eu.mailgun.net`

## 3.3.6

### front-end

#### datepicker
- adds support for `readonly` (acted as disabled before)
- adds `disable-clear`

#### directives
- adds `v-long-click` directive; should provide the duration when used (`v-long-click:2000`)

#### forms
- disables `dirty` monitoring when `disable-state` is used
- adds `self` to the ready event; now the whole form component can be used as an object from the outside

#### select
- improves fetching / debouncing strategy
- adds a `selection` event that returns option objects
- fixes non string labels highlighing / matching
- fixed issue with matching when using non-lower case query string
- fixes issue with highlighting special chars

#### tables
- shrinks default row text to `0.9em`
- adds support for cents
- adds support for `searchModes`, `searchMode`

#### ui
- adds vuecal2 support; the calendar menu is still WiP
- fixes `numberFormat` filter to always display the specified digits
- adds support for `font-awesome-layers-text` component; can be accesed under the global `<falt/>`
- fixes a bug in bookmarks when trying to focus the bookmark before the page was mounted
- fixes default logo

#### uploader
- adds `open-file-browser` event

### back-end

#### addresses
- adds tests

#### calendar
- adds reminders; still WiP

#### companies
- updates the form and table classes to allow easier extenting via service container

#### cli
- fixes stub for table builder

#### documents
- return a document resource from `Store.php` controller

#### forms
- adds tests
- adds support for `disable-filtering` select option

#### helpers
- adds `DateAttributes` trait
- adds `InCents` trait

#### history-tracker
- rewrites logic that handles tracked attributes

#### localisation
- fixes routes for `localisation:merge` command

#### people
- updates the form and table classes to allow easier extenting via service container

#### ro-addresses
- adds an option resource

#### roles
- updates `warning` to `warn` in `enso:roles:sync` command

####  tables
- adds `searchMode` / `searchModes` to allow full use of table indexing on huge tables
- adds support for cents
- adds tests
- fixes search with multiple arguments on relations
- fixes max / min filters

#### versioning
- improves logic
- fixes error repoting
- fixes various edge case bugs

### Upgrade steps
- `composer update`
- `php artisan enso:upgrade`
- `yarn upgrade && yarn`

Enjoy!

## 3.3.5

### front-end

### accessories
- adds an `icons` boolean prop to the `Accessories.vue` component
- improves `Document.vue` for long names
- adds a `compact` boolean prop for `Comments.vue`
- adds `disable-state` on `EnsoForm` in `AddressForm`

### card
- improves (reduces) card control spacing

### datepicker
- adds Ukranian language support

### filters
- fixes `IntervalFilter` (now works with `v-model=interval`) & `DateIntervalFilter`

### forms
- cascades `fetch()`, `setOriginal()`, `hideTab()`, `showTab()` from the core component
- adds a `ref="field"` on the used form field component in `FormField.vue`
- fixes vuex state handling => keeps a copy of the form data
- automatically hides tabs with empty sections

### mixins
- adds a `files.js` mixin

### select
- emits an `update` event when a change is made from inside the component
- fixes `fetch()` cascading from the core component
- allows multi argument search with highlighting matched

### tables
- fixes custom totals
- adds support for nested attributes
### ui
- fixes `.` (dot) date separator handling
- adds a `setPageTitle` action in store.js that handles page title & bookmark renaming
- adds `hideFooter` / `showFooter` mutations in `layout.js` store module
- extracts `AvatarList.vue` component from `Team.vue`
- fixes settings bar padding that was causing the appearance of a horizontal scroll in some cases

### uploader
- adds a compact option

### back-end

#### addresses, comments, discussions, documents
- fixes avatar loading for `TrackWho` resource
- adds a `MorphOne` relation besides the `MorphsMany`
- adds `UpdatesOnTouch

#### data-export
- adds missing imports in `ExcelExport` service

#### data-import
- changes sheet normalisation from camel case to snake case
- ignores empty rows on import

#### examples
- fixes `DateInterval` filter use

#### files
- fixes avatar loading for `TrackWho` resource

#### helpers
- adds translation in `EnsoException`
- adds `AvoidsDeletionConflicts` model trait => reports `ConflictHttpException` when a model cannot be deleted due to DB restrictions
- adds `MapsRequestKeys` request validator => adds a `mapped()` method to the request validator that maps the keys to snake case
- adds `UpdatesOnTouch` model that ensure that parent models trigger the `updated` event when using the `protected $touches` property. Does that by rewriting `Model`'s `touchOwners()` method.
- adds `Decimals` class that exposes a set of static helpers for decimals operations / comparisons using bcmath

#### forms
- adds `hideSection($fieldName)` / `showSection($fieldName)` & `hideTab($tabName)` / `showTab($tabName)` helpers for the form object

#### io
- fixes avatar loading for TrackWho

#### people
- adds missing `position` & `is_main` pivot information on `company()` & `companies()` relations

#### ro-addresses
- adds county `Options` controller & route

### roles
- refactors the `Sync` command

#### select
- allows multi argument searching
- adds a `comparisonOperator` option in `select.php` config
- adds tests

#### tables
- refactors excel export to work with authenticated user (needed in some scopes)
- fixes interval filters (both date & non date)
- implements dependency injection for resolving the table class
- adds `searchable` ability for relations / nested attributes
- adds `cents` option in meta that divides totals to 100, if the case

#### teams
- fixes n+1 query problem when loading resources in index controller

#### track-who
- refactors resource to enfore correct pre-loading of relations

#### ui
- fixes enums translations

#### versioning
- adds a `lockFor($version)` method
- refactors the trait

### Upgrade
- `composer update && yarn upgrade && yarn`

## 3.3.4

Bug fixes, small additions & refactors, dependencies updates

### BE

#### calendar
- fixes the request validator and `allowed` scope
#### data-export
- fixes contracts import `ExcelExport` service
#### data-import
- fixes excel seeder in
- adds a small refactor in `Template.php`
#### roles
- fixes `enso:roles:sync` throwing error when the roles folder was missing
#### tables
- fixes multiple select filters in tables
- fixes `filters` prop parsing
- fixes interval filters
- refactors excel export to work with scopes that need the authenticated user

### FE

#### forms
- cascades `setOriginal()` helper in `vue-form` and `enso-form`
#### ui
- adds a `disable-state` boolean prop for forms that don't need state
- adds a toaster confirmation when writing role config files
- adds dot separator support for date format in enso-ui
#### tables
- adds payload to postEvents
- fixes `clickable` on columns hidden due to responsiveness
#### wysiwyg
- patches the `Link` extension to open links in new tab / windows

### Upgrade
- `composer update && yarn upgrade && yarn`

## 3.3.3

### ui
- adds bookmarks store module
- fixes bookmark focus
- adds ability to hold state

### forms
- enhances the errors & changes control, adding the ability to undo
- integrates with the new bookmark's state to hold changes
- adds a fill helper
- exposes dirty & fill in vue form / enso form

### general
- fixes small bugs
- updates dependencies

Run `composer update` / `yarn upgrade && yarn` to upgrade existing projects

## 3.3.2
- adds preview in tables
- fixes excel export when using filters / sorts / search in tables
- fixes multiple select filter in tables
- refactors AddressForm to allow easier customization
- fixes in CLI controllers / routes / model bindings generation
- fixes dateinterval filter max value
- fixes menus treeBuilder to consider only permissions available to the auth user's role
- fixes other minor bugs

Run `composer update` / `yarn upgrade && yarn` to upgrade existing projects

## 3.3.1

- improved forms
    - added a reusable `Action` component
    - added visual indicators when a form is dirty or has errors
    - fixed a bug when constructing the `formData` object
- fixed all templates where text fields were declared null as default instead of an empty string
- fixes table's db date format handling. We have now a config option for that
- fixes examples

When upgrading you should consider refactoring custom form action, if any, using the new `Action` component.

## 3.3.0

# Purpose
- rename BE repos - when the organization was started we had not really followed 
a consistent naming convention for the packages. But now we do :)
- use the same naming convention for migrations
- refactor controllers and request validators towards SRP, 
with an invokable controller for each route endpoint 
- switch to using dependency injection wherever possible in controllers, 
to allow easier & cleaner customization of Models ($fillable, relations etc), 
request validators, from builders, table builders, etc.
- replace the class registration part of the configs with service providers
- refactor `is_null($var)` usages to `$var === null`, following best practices
- remove `activity-log`'s default inclusion from all packages except from `teams`
- following several requests and real world requirements, 
change the relationship between people and companies from `N - 1` to `N - N`

# Changes

Note that all back-end packages (and repositories) were renamed, 
even if in some cases, only the casing changed. Where needed namespaces were updated.

Below you'll find the old package name in parenthesis.

## Back-End:

### action-logger (old ActionLogger)

### activity-log (old ActivityLog)
- will be soon refactored from scratch; 
- the Model linking mechanism will be updated to using a registration service provider
- that this package is not yet production-ready

### addresses (old AddressesManager)
- renamed namespace from `AddressesManager` to `Addresses`
- renamed migration (applied by the rename-migrations command)
- refactored controllers
- added `$fillable` for the `Address` model and `$request->validated()` in the controllers
- improved request validators
- does not allow to delete the default address if secondary addresses are present
- touches parent on model creation

### avatars (old AvatarManager)
- renamed namespace from `AvatarManager` to `Avatars`
- renamed `Classes` folder to `Services`
- refactored controllers

### calendar (old Calendar)
- refactored controllers
- small refactor
- this package is work in progress, will be updated and is not yet production-ready

### comments (old CommentsManager)
- renamed namespace from `CommentsManager` to `Comments`
- renamed migration (applied by the rename-migrations command)
- refactored controllers
- improved request validation
- added a `TaggedUser` resource
- touches parent on comment creation
- removed the `LogsActvity` trait from the model

### control-panel-api (old ControlPanelApi)
- refactored controllers
- adds `.styleci.yml`

### charts (old Charts)
- renamed `Classes` to `Factories`
- dropped that `Chart` suffix for all builders (eg. `BarChart` to `Bar`)

### cli (old StructureMigration)
- added support for namespaced models 
- updated stubs for single responsibility controllers
- added `.styleci.yml`

### companies (old Companies)
- renamed migration (applied by the rename-migrations command)
- refactored controllers
- refactored request validation
- added `$fillable` to the model and used `$request->validated()` in controller methods
- removed the `LogsActvity` trait from the model
- renamed `scopeTenants` to `scopeTenant`
- `$company->mandatary()` is now a helper method
- `$company->people` was added for the new relation

### core (old Core)
- refactored controllers
- refactored request validators
- added the `UserGroups` enum, bound to the app's service container under the `user-groups` alias; 
the enum is also present in the app state
- added the `enso:rename-migrations` command
- updated the `enso:upgrade` command for a smoother upgrade
- web sockets is available in the app state
- `UserGroup::Admin` constant has been removed

### data-export (old DataExport)
- added an `ExcelExport` service
- implements `FileServiceProvider`

### data-import (old DataImport)
- renamed migration (applied by the rename-migrations command)
- renamed `Classses` to `Services`
- refactored controllers
- removed the `LogsActvity` trait from the model

### discussions (old Discussions)
- renamed migration (applied by the rename-migrations command)
- refactored controllers
- refactored request validators
- removed the `LogsActvity` trait from the model
- touches parent on model creation

### documents (old DocumentsManager)
- renamed migration (applied by the rename-migrations command)
- renamed namespace from `DocumentsManager` to `Documents`
- refactored controllers
- removed the `LogsActvity` trait from the model

### files (old FileManager)
- renamed namespace from `FileManager` to `Files`
- renamed `FileManager` service to `Files`
- added a publishable `FileServiceProvider` that allows registering the visible files in the File menu 
and drops the `VisibleFiles` enum
- added a `FileBrowser` facade
- improved the `files.php` config
- added an `attach` method to the `Attachable` contract that takes a `Illuminate\Http\File` object
- added explicit exceptions
- refactored controllers
- improved services
- added return types for `Attachable` & `VisibleFile` contracts

### forms (old FormBuilder)
- renamed namespace from `FormBuilder` to `Forms`
- renamed `Classes` to `Services`

### helpers (old Helpers)
- refactored `Obj` to extend `Collection`
- exposed the `constants()` method in `Enum`

### history-tracker (old HistoryTracker)

### how-to (old HowToVideos)
- renamed migration (applied by the rename-migrations command)
- refactored controllers
- removed the `LogsActvity` trait from the model

### impersonate (old Impersonate)
- refactored controllers

### localisation (old Localisation)
- refactored controllers
- refactored validation 
- renamed `Classes` to `Services`

### logs (old LogManager)
- renamed migration (applied by the rename-migrations command)
- renamed namespace from `LogManager` to `Logs`
- refactored controllers

### menus (old MenuManager)
- renamed namespace from `MenuManager` to `Menus`
- refactored controllers
- refactored request validators
- added the organize action - allows drag & drop menu reordering for menus having the same parent

### multitenancy
- moved `Connection` enum to `Enums` folder
- renamed `Classes` to `Services`

### migrator (part of the old StructureMigration)
- moved to its own package
- renamed class `StructureMigration` to `Migration`

### people (old People)
- refactored controllers
- refactored request validation
- added `$fillable` to the model and used `$request->validated()` in controller methods
- removed the `LogsActvity` trait from the model
- changed the relationship with companies from `N - 1` to `N - N`:
    - the pivot table has three additional attributes, `position`, `is_main` & `is_mandatory`
    - `$person->company()` is now a helper method
    - `$person->company()->pivot->position` will return the person's position
    - `$person->companies` was added for the new relation, always loads with `position`

### permissions (old PermissionManager)
- renamed namespace from `PermissionManager` to `Permissions`
- renamed enum from `PermissionTypes` to `Types`
- refactored controllers
- refactored request validation

### rememberable (old Rememberable)

### roles (old RoleManager)
- renamed namespace from `RoleManager` to `Roles`
- refactored controllers 
- refactored requests 
- added `Roles` Enum, bound to the app's service container under the `roles` alias
it replaces the old `AdminId` and `SupervisorId` role constants; 
- the roles enumeration is now also available in the front end via the app state
- renamed `Classes` to `Services`
- allows nullable `menu_id` - useful for users that can't access the app but can still be notified by email

### searchable (old Searchable)
- implements a publishable `SearchServiceProvider` that allows registering the searchable models
- small refactor

### select (old Select)
- renamed the `options` method from the `OptionsBuilder` trait to `__invoke`
- added a `select.php` config that allows customizing the default `trackBy` & `queryAttributes`

### tables (old VueDatatable)
- renamed namespace from `VueDatatable` to `Tables`
- renamed config file from `datatable.php` to `tables.php`
- renamed `Classes` to `Services`
- added `Init`, `Data` traits for invokable controllers
- renamed the `excel` method to `__invoke` in the `Excel` trait
- renamed the `action` method to `__invoke` in the `Action` trait

#### Note
The `Datatable` trait will be deprecated soon, 
but if you like the old approach better you can recreate the trait locally and use it as required
 
### track-who (old TrackWho)
 - removed config & AppServiceProvider
 - fixed `DeletedBy` attribute order in `forceFill()`

### tutorials (old TutorialManager)
- renamed migration (applied by the rename-migrations command)
- renamed namespace from `TutorialManager` to `tutorials`
- refactored controllers
- extracted a `Tutorial` resource from the `TutorialIndex` response
- renamed `TutorialIndex` response to `Show`
- added a `tutorials.php` config
- removed the `LogsActvity` trait from the model

### versioning (old Versioning)

## Front-End / UI

### dropdown
- adds the ability to open the dropdown upwards if the room below is insufficient
- exposes `triggerEvents`, all the components that use dropdown were updated accordingly

### forms
- fixes section slot for tabs
- fixes `switch-field` height 

### ui
- added menu drag and drop reorder option via settings switch 
- removed deprecated `__.js` module
- echo is now instantiated on window (accessible as `window.Echo`)
- there is a new `websockets` module, 
which provides the `ioChannel`, `privateChannel` and `pusher` configurations

### vue-switch
- added support for control-label via slot
- `vue-switch` can now be toggled also with the Enter key
 
# Upgrade Steps
- update in `composer.json`
    - `core` to 4.3.*  
    - `control-panel-api` to `2.2.*` (replace controlpanelapi)
    - `examples` to 1.2.* (if needed)
    - remove `laracasts/generators`
- update in `package.json`
    - `@enso-ui/ui` to `~1.3.0`
- run `composer update`
- run `yarn upgrade && yarn` (if `patch-package` fails, you should update your patches)
- run `php artisan enso:rename-migrations`     
- run `php artisan migrate`
- run `php artisan enso:upgrade`
- update `App\User` with the proper imports/namespaces
- remove from your route file the `@options` action method for all select controllers that are using the `OptionsBuider` trait
- remove from your local route file `@excel` action method if you want to keep the old style table controllers
**OR**
- refactor all your table controllers to single responsibility controllers. 
Use a package like `tutorials` for an example of how this is achieved.

- configs:
    - remove the paths key from `config/enso/config.php`
    - update `config/enso/files.php` according to the one from the `files` package
    - rename `config/enso/datatable.php` => `config/enso/tables.php`
    - update `config/enso/imports`, if necessary (example importer)
    - update `config/enso/searchable` according to the one from the searchable package. To add your models publish and use the new `SearchServiceProvider`

- factories and seeders:
    - update `MenuFactory`
    - update `PermissionFactory`
    - update `TutorialFactory`
    - update `CommentFactory`
    - update `RoleFactory`
    - update `UserFactory`
    - update `PersonFactory`
    - update `CompanyFactory`
    - update `CountrySeeder`
    - update `LocalitySeeder`
    - update `UserGroupSeeder`
    - update `UserSeeder`
    - update `RoleSeeder`

- search and replace (where applicable)
    - `LaravelEnso\StructureManager\app\Classes\StructureMigration` => `LaravelEnso\Migrator\app\Database\Migration`
    - ` extends StructureMigration` => ` extends Migration`
    - `LaravelEnso\Charts\app\Classes` => `LaravelEnso\Charts\app\Factories`
    - `LaravelEnso\Charts\app\Classes\BarChart` => `LaravelEnso\Charts\app\Factories\Bar`
    - `LaravelEnso\Charts\app\Classes\BubbleChart` => `LaravelEnso\Charts\app\Factories\Bubble`
    - `LaravelEnso\Charts\app\Classes\DoughnutChart` => `LaravelEnso\Charts\app\Factories\Doughnut`
    - `LaravelEnso\Charts\app\Classes\LineChart` => `LaravelEnso\Charts\app\Factories\Line`
    - `LaravelEnso\Charts\app\Classes\PieChart` => `LaravelEnso\Charts\app\Factories\Pie`
    - `LaravelEnso\Charts\app\Classes\PolarChart` => `LaravelEnso\Charts\app\Factories\Polar`
    - `LaravelEnso\Charts\app\Classes\RadarChart` => `LaravelEnso\Charts\app\Factories\Radar`
    - `LaravelEnso\FileManager\app\Classes\FileManager` => `LaravelEnso\Files\app\Services\Files`
    - `LaravelEnso\Permissions\app\Enums\PermissionTypes` => `LaravelEnso\Permissions\app\Enums\Types`
    - `LaravelEnso\FormBuilder\app\Classes` => `LaravelEnso\Forms\app\Services`
    - `LaravelEnso\AddressesManager` => `LaravelEnso\Addresses`
    - `LaravelEnso\CommentsManager` => `LaravelEnso\Comments`
    - `LaravelEnso\DocumentsManager` => `LaravelEnso\Documents`
    - `LaravelEnso\VueDatatable` => `LaravelEnso\Tables`
    - `LaravelEnso\Tables\app\Classes\Table` => `LaravelEnso\Tables\app\Services\Table`
    - `LaravelEnso\PermissionManager` =>  `LaravelEnso\Permissions`
    - `LaravelEnso\RoleManager` => `LaravelEnso\Roles`
    - `LaravelEnso\FileManager` => `LaravelEnso\Files
    - `UserGroup::Admin` constant has been removed and should be replaced with the `UserGroups::Admin` enum
    - `Role::AdminId` and `Role::SupervisorId` constants are no longer available and should be 
        replaced with `Roles::Admin`, `Roles::Supervisor`; make sure to import the `LaravelEnso\Roles\app\Enums\Roles` enum
    - since the `Company` model's `scopeTenants` was renamed to `scopeTenant` search for its use and update as needed
    - `LaravelEnso\VueDatatable` with `LaravelEnso\Tables`
    - `LaravelEnso\Tables\app\Classes` with `LaravelEnso\Tables\app\Services`

- refactor local forms, if required:
    - local `person.json` template (`company_id` changed to company)
    - local `company.json` template (`mandatary_id` changed to mandatary)

- if using/customizing/extending addresses, people or companies (eventually any package):
    - you should switch to leveraging dependency injection and bind your local implementations to the package's models, validator classes
    - note that for both Person, Company and Address models the `$guarded` property was replaced with the explicit `$fillable`. 
    - if extending any of these models, don't forget to overwrite the `$fillable` property 
- if you're using/extending validators, controllers, models from the refactored packages, at minimum, 
you will need to update your imports
- update `phpunit.xml` with the one from this repo
- make sure that in `webpack.mix.js` you don't have `sourceMaps()` for production
- don't forget to implement Searchable and Files for your required models. You can use `LaravelEnso\Companies\SearchServiceProvider` as example.  

Enjoy!

## 3.2.3
- fixes minor bugs
- updates dependencies
- updates changelog for 3.2.1

## 3.2.2
- updates changelog for 3.2.1

## 3.2.1

### Changes
- adds RTL
- brings back excel export in dataimport main table
- fixes the multi root render bug in Tabs
- exposes a `key` method in CoreTabs
- visually refines Adresses, Comments, Documents, Discussions and People widgets
- adds docker setup
- exposes `constants()` from `Enum`
- fixes a bug in IOStatuses when running async operations
- fixes example table page
- other minor bugs and fixes

## Upgrade existing projects
- update `webpack.mix.js` to add the rtl themes
- copy `patches/bulma-rtl+0.7.1.patch` from Enso's repo to your local project
- `composer update`
- `yarn upgrade && yarn && yarn run dev`
- add to your `config/enso/themes/php` 
```php
'light-rtl' => '/themes-rtl/light/bulma.min.css',
'dark-rtl' => '/themes-rtl/dark/bulma.min.css',
```
- make sure that you have the update versions for `LanguageFactory` & `LanguageSeeder`, in your project

## 3.2.0

### Changes -> laravel-enso

#### ActivityLog
- uses Enso's `dateFormat` in interval filter

#### AddressesManager
- corrects a label
- refactor params validation in a method to allow easier customization of the request validator

#### CommentsManager
- adds a `humanReadableDates` config option that defaults to true

#### Core
- adds RTL support, still WiP
- adds `ioStatuses` to state enums
- fixes `enso` mail theme
- removes the duplicate favico from index.blade.php
- refactors/improves the password validator

#### DataExport
- adds `created_by` to fillable
- adds an `ExcelExport` class helper; will be documented soon.

#### DataImport
- adds a time column in the main table
- sorts the main table by the latest imports as default
- makes the type selector filter the main table too
- adds `created_by` to fillable
- improves row sanitization
- fixes a bug on imports that don't have a custom validator
- fixes the importer / validator reusability; saves several instantiations on larger imports
- fixes the statuses to work with IO

#### Examples
- fixes the example table bugs

#### FileManager
- refactors / improves the interval filter; now enso's `dateFormat` is used

#### FormBuilder
- fixes a bug when using custom routes
- adds autosave support
- adds a `labels` config & template option that turns labels into placeholders when set to `false`
- fixes the validation message for template's `columns` attribute
- hides forbidden actions instead of just disabling
- adds a debounce option, useful with autosave forms

#### IO
- moves the broadcast channel from the main repo in the IO package
- improves progress reporting for imports / exports. Now admins / supervisors can see all IO operations while lower roles can only see their own activity

#### Localisation
- adds RTL support
- fixes the `Flag` label in the edit form

#### LogManager
- improves the index page
- adds `reload` and `delete` controls to the show page

#### Notifications
- moves the notifications broadcast channel from the main repo in the notification package

#### People
- fixes `person.json` form template

#### Rememberable
- refactors the package from scratch
- the two traits were merged into just one
- added the option to cache models `forever`
- easier interface
- better key naming
- allows caching models with non standard id's

#### Select
- fixes pagination
- adds support for api resources instead of models

#### StructureManager
- uses the model to determine the table name in `index.stub`
- removes redundant `animated fadeIn` in form stubs
- uses the correct table component in `index.stub`
- fixes the index page name

#### TrackWho
- updates the logic to allow manual management when no `auth()->check()` is available

#### VueDatatable
- uses Enso's `dateFormat` when for interval filters
- fixes new sheet generation when exporting more than 1 mil rows
- improves `cacheTrait` to autodetect the model's table
- fixes a bug when using a custom `lengthMenu`
- makes the tables controls customizable
- fixes the default sort when used in template

#### TutorialManager
- fixed bug: tutorial background was sometimes blocking the highlighted element

### Changes -> enso-ui

#### accessories
- changes the classes for comment dates and controls to ensure a better contract when using the dark theme 
- adds back the `controls` prop for documents
- 
#### card
- removes the card in the nextTick after emitting the `remove` event. This way the event can be used for custom logic, like router navigation

#### chart
- adds the fetched data to the `fetched` event

#### filters
- adds a `disabled-options` prop in date-filter

#### forms
- improves error focusing on submit (scrollIntoView)
- correctly cascades tabs events
- adds a template prop

#### tables
- adds axios request canceler for concurrent data fetch()
- fixes a visual issue on edge when table is reloading
- fixes debounce

#### themes
- fixes tag counter `EnsoTabs` when using the dark theme
- fixes the navbar-end disapearing when window width was 1039px-1087px
- makes vue filters scrollbars invisible
- fixes the `ModalCard` background when using the dark theme

#### transitions
- names all transitions
- adds `HorizontalFade` && `HorizontalSlide` for RTL

#### ui
- fixes ravenKey in store
- allows using `:` in lang keys
- adds a `_filter()` helper for the `Enum` to work with Enso's filters
- fixes filter change in files
- makes the logo clickable in the AuthForms; on click navigates to the login form
- adds `unicode2php` convertor for dates

### Upgrade steps

- refactor the deprecated FE helpers (global mixins) with the new injected ones available in v3.x:
  - `__` to `i18n`
  - `handleError` to `errorHandler`
- refactor the `Rememberable` use according with the new [version](https://docs.laravel-enso.com/packages/rememberable.html)
- when `TableCache` trait is used you can safely drop the `protected $cachedTable = 'tableId';` property; now the trait autodetects the table's name
- update the core dependency in `composer.json` to: `"laravel-enso/core": "4.2.*"`
- update the ui dependency in `package.json` to: `"@enso-ui/ui": "~1.2.0",`
- run the following:
  - `composer update`
  - `php artisan vendor:publish --tag=localisation-factory --force`
  - `php artisan vendor:publish --tag=dataexport-config`
  - add the `labels` config option in `config/enso/forms.php`
  - add the `humanReadableDates` config option in `config/enso/comments.php`
  - rename the `buildingTypes.Comercial` to `buildingTypes.Commercial` in `config/enso/addresses.php`
  - if you are using the `enso` theme for mails then run `php artisan vendor:publish --tag=enso-email --force`
  - remove from `routes/channels.php` both `App.User.{id}` and `operations.{userId}` entries 
  - if you're not using multitenancy remove from `config/databases.php` the `system` and `tenant` connections and update your env to use `mysql`
  - `php artisan enso:upgrade`
  - `yarn && yarn upgrade && yarn run dev`
  
## 3.1.1

- fixes typo in calendar
- fixes comment tag notifications
- improves data import cell sanitization
- improves new operation handling in `IO.vue`
- improves slot exposure in forms
- fixes bug that was preventing hiding sections without visible fields
- adds back submit button animation in forms
- fixes selected value handling in vue select when using objects

To upgrade:

- remove `v-click-outside` from extracted vendors in `webpack.mix.js` and from `package.json`
- `yarn upgrade && composer update && yarn run dev`

## 3.1.0

### Changes

- improves custom fields in forms (slots). Now you can use the generic `FormField` in almost all cases. If you want to use the specific fields (`SelectField`, `InputField`...) don't forget to manually add the label
- unifies `phpDateFormat` and `jsDateFormat` in one single `dateFormat` prop, in `config/enso/config.php`
- adds a new Calendar package. Note that the calendar is still WiP
- fixes various bugs
- fixes npm dependencies to keep track of minor versions

### Upgrade steps:

- update in `package.json` all `@enso-ui/...` deps by replacing the `^` with `~` (`"@enso-ui/accessories": "~1.0.0",`)
- update in `package.json` the `form` and `ui` deps to
```
"@enso-ui/forms": "~1.1.0",
"@enso-ui/ui": "~1.1.0",
```
- update in `composer.json` -> `"laravel-enso/core": "4.1.*"`
- replace `phpDateFormat` and `jsDateFormat` with `dateFormat` in `config/enso/config.php`
- search the whole frontend by `-field` and replace all the specific fields with `form-field` in custom forms. Don't forget to also import `FormField.vue` from the same location.
- `composer update && yarn install && yarn upgrade`
- `php artisan migrate`
- publish the calendar config and provider if you need customizations (will be documented in the near future)
- `yarn dev`

## 3.0.3

- adds `horizontalBar` chart type in Charts
- exposes 
- fixes a bug in Filemanager for filenames containing non ascii chars
- adds a `responsive` boolean flag in vue table config / template that controls if the table is horizontally responsive / uses scroll
- fixes the table stubs in StructureManager
- fixes label generation in AddressManager when having `country_id` null
- fixes `VueTable`'s unique `key` for `tr` that was causing table freezes in some edge cases
- fixes `expanded` management in `VueTable`
- adds missing `notificationEvents` in `Notifications`
- adds `Tag` to export list in the select package

## 3.0.2

- fixes two bugs related to hidden rows (due to responsivity)
- fixes closing modal on deleting from forms
- minor other bug fixes
- adds the `ProgressCircle` component (@enso-ui/progress-circle)
- updates all deps
- adds a `localisation(bool)` helper in `Enum`
- adds an `option()` helper to the `Chart` builder

## 3.0.1

- tens of small bugs fixed
- improves file index page
- improved docs
- adds scroll to top

### Upgrade

- update in your `router.js`:

```js
const router = new Router({
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { x: 0, y: 0 };
    },
});
```

## 3.0.0

## General

### Goals

We are always trying to move towards logic normalization (be it back end or front end), having the packages encapsulated, easy to work on, maintain, extend and customize.

Keeping the front-end assets together with the back-end code as well as having to publish everything on updates, managing what to overwrite or keep separate, was clearly not natural.

V3 is a big step in the right direction with the extraction of the front-end assets from the Composer packages and their move to individual NPM repositories.

### Rewrite

All the VueJS components have been rewritten (around 20k LOC have been updated/refactored), and now there is a more logical flow, components communicate better with each other, there's a greater degree of re-usability, removed duplicated logic, less lines of code (the old middleware is gone) and even a bit of extra functionality.

We went for "composition over inheritance" favoring slots over props.

Where it was feasible we created renderless components.

We made use of dependency injection to replace global mixins or to share the state in specific components / libs.

After the refactor most of the middlewares were removed.

We tried to remove all the `scoped` scss styling from the vue components, opening them up for local customization

We added friendly aliases for imports.

### NPM packages

We strived to create packages for every piece of functionality. This is still an ongoing process.

All the new packages can be found under the @enso-ui organisation on both github and npmjs.

For the active components we went for encapsulating the front end behavior in renderless components, and creating template wrappers.

### Theming and styling

As before, Enso ships with two themes, one dark and one light. 
The themes and the overall styling have been organized better, offer the possibility of easier customization and can be found in the @enso-ui/themes package.

It's easier to style specific components on both themes by creating a single scss file that uses variables, and place this file under the `/components` folder

### Development & Customization

If previously, the best/only way to customize some of the pages was to create your own local copies and then making sure you overwrite the default Enso pages with your own versions, now you can customize the assets much easier. You can do this by either rewriting core-routes in your local project and point to local pages, or you can use (patch-package)[https://www.npmjs.com/package/patch-package] and customize anything in a package without having to manually maintain the differences.

You can easily write your own version for any asset while taking advantage of the existing behaviour contained in the renderless components. This even makes switching Enso from Bulma to the CSS framework of your choice much easier.

Therefore, for most packages, you have the possibility to:
- customize the components within the packages and then use patch-package, or
- locally create your own version of the component  and change the imports to use your local component

For the theme customization you can:
- customize the styles within the theme package and then use patch-package, or
- create your own theme, locally, and change the theme import in webpack.mix.js

You can develop your own components and pages by importing just the bits and pieces you need.

Remember, should you need a custom behavior for a component, or your own version of a component with different styling you can build it your own way as most complex components have the core logic encapsulated in renderless components.

If making package fixes we kindly ask you to also open a github issue and a pull request, since this helps the whole community.

### Enso vs Standalone

In the past, even if we're done our best to make some of the components work outside of the Enso ecosystem, there hasn't been a clear distinction between the integrated and the standalone components.

With the rewrite, all the packages were designed to be also Enso independent, and may be used in any vue project.

If you are developing your project within the Enso ecosystem, the Enso versions of the components offer a tighter integration into the framework, 
automatically determining and using:
- the user's locale
- dynamically determined routes
- the internationalization helper
- the app's error handling
- the user's date format

### Bug Fixes & Improvements
- page transitions are in better sync
- the theme changing system was refactored and it's more efficient
- the bookmarks bar is sticky
- the document title is properly handled / translated
- totals & numbers alignment in vue table
- several levels of span removed in vue table
- several edge cases fixed in vue-select

## New Packages

### Accessories (@enso-ui/accessories)

#### Addresses.vue, Comments.vue, Documents.vue
- the `controls` property is no longer available

#### AddressesCard.vue, CommentsCard.vue, DocumentsCard.vue
- the `open` property has been replaced to `collapsed`

### Charts (@enso-ui/charts)

#### Chart.vue
- a new `formatter` property is available, type `function` used to format the datalabels values

#### ChartCard.vue
- a new `errorHandler` property is available

#### EnsoChartCard.vue

### Forms (@enso-ui/forms)

#### VueForm.vue
- a new `errorHandler` property is available
- whole sections can now be inserted into custom slots made available for this purpose
- the following events are available:
    - `ready`
    - `loaded` - each time the template gets fetched
    - `submit`
    - `show`
    - `destroy`
- the Tabs component events are cascaded

### Datepicker.vue (@enso-ui/datepicker)
- the `name` property is no longer available

### Divider.vue (@enso-ui/divider)
- has a correspondent divider.scss in @enso-ui/themes, now looks good on the dark theme.

### Money.vue (@enso-ui/money)

### VueSwitch.vue (@enso-ui/switch)
- the `required` property is no longer available
- the `type` & `size` props is no longer available
- Bulma classes can be set directly on the component (`is-large is-warning`)

### Wysiwyg.vue (@enso-ui/wysiwyg)
- `tiptap` was updated from 0.17.* to 1.7.*
- now supports tables

### Select.vue (@enso-ui/select)

#### VueSelect
- the `limit` property has been renamed to `paginate`
- the trackBy is now sent from the front-end to the back-end, which means this property is no longer needed in the back-end
- the following events are now available:
    - `select`
    - `deselect`
    - `clear`
    - `add-tag`
    - `input`

#### EnsoSelect
- new

### Filters (@enso-ui/filters)

#### VueFilter.vue
- the `length` property has been replaced with `paginate`
- the `validator` property has been removed
- if `icons` is used then the options object will use the `icon` key instead of `label`

#### EnsoFilter.vue
- new

#### DateFilter
- last week and last month were changed to 7 days & 30 days

#### EnsoDateFilter.vue
- new

#### EnsoDateIntervalFilter.vue
- new

#### EnsoIntervalFilter.vue
- new

#### VueSelectFilter.vue -> SelectFilter.vue

#### VueSelectFilter.vue -> SelectFilter.vue

#### EnsoSelectFilter.vue
- new

#### IntervalFilter.vue
- is located in the `@enso-ui/filters` package

### Notification.vue -> Toastr.vue (@enso-ui/toastr)
- the `container` property has been removed and `toastr-notifications` is used by default
- the `i18n` property has been removed
- the `html` property has been added

### Card.vue (@enso-ui/card)
- the `nested` property has been removed. The nesting is handled automatically
- the `icon` & `title` properties have been removed, in favor of using the default slot of the  `CardHeader` component
- the `search` property has been deprecated & removed
- the `badge` property has been removed, in favor of using the default slot of the `CardBadge` component
- the `refresh` property has been removed, in favor of using the `CardRefresh` component
- the `fixed` property has been deprecated & removed. The card by default is non collapsible but when using the `CardCollapse` component within the `controls` slot, it becomes collapsible
- the `removable` property has been removed in favor of the `CardRemove` component
- the `controls` property has been deprecated & removed
- the `overlay` property has been renamed as loading
- the `scrollable` property has been deprecated & removed
- the `transition` property has been added and is required if using transitions

Note that now the card has become more modular, so instead of giving properties, you'll be using other components to build the card to your heart's desire, achieving the previous functionality and going beyond.

### Dropdown.vue (@enso-ui/dropdown)
- added `disabled` property
- `height` & `width` were removed and need to be managed in the wrapper component
- `hidesManually` has been renamed to `manual`
- used the new dropdown-indicator
- built upon a renderless component
- handles keyboard events: Esc, Tab, Enter

#### DropdownIndicator.vue (@enso-ui/dropdown-indicator)
- reusable arrow with state

### Modal (@enso-ui/modal)

#### Modal.vue (@enso-ui/modal)
- the `card` property has been removed
- the `container` property has been replaced with `portal`

#### ModalCard.vue
- the `container` property has been replaced with `portal`

#### Overlay.vue -> Loader.vue (@enso-ui/loader)
- the component has been removed and replaced with `Loader`
- `Loader` is now located in the `@enso-ui/loader` package
- the loader now has an improved animation and uses a transition for a better experience

#### Popover.vue -> Confirmation.vue (@enso-ui/cofirmation)

### Tabs (@enso-ui/tabs)

#### Tabs.vue
- the component design has been updated under the hood, switching from event based communication to dependency injection
- the following events are now available:
    - `registered`, when a new tab is registered
    - `removed`, when a tab is removed
    - `selected`, when a tab is selected
    - `activated`, when a tab is made active

#### EnsoTabs.vue
- the `alignment` property has been removed

#### Tab.vue
- the `disabled` property has been removed
- the following methods are exposed:
  - activate
  - enable
  - disable
  - remove

### Typeahead

#### Typeahead.vue (@enso-ui/typeahead)
- is now located in the `@enso-ui/typeahead` package
- the `length` property has been replaced with `paginate`
- the `validator` property has been removed as now the regex validation is always used and can be customized

#### EnsoTypeahead.vue
- new

### Uploader (@enso-ui/uploader)

#### FileUploader.vue -> Uploader.vue

#### EnsoUploader.vue (@enso-ui/uploader)
- new

### ProgressBar.vue (@enso-ui/progress-bar)
- new

### Tables

#### EnsoTable.vue
- new

#### Themes (@enso-ui/themes)
- it contains the two themes (light & dark) that come with Enso by default.
- it contains components scss

### Transitions (@enso-ui/transitions)
- this is a new package

#### Fade
#### FadeDown
#### FadeLeft
#### FadeRight
#### FadeUp
#### SlideDown
#### SlideLeft
#### SlideRight
#### SlideUp
#### Zoom

#### UI (@enso-ui/ui)
- it contains the main `App.vue` component as well as all the core dependencies such as layouts, middleware, pages, filters, mixins, plugins, routes, etc.

### VueComponents
- was deprecated and split into multiple packages

#### InfoBox.vue
- the component has been removed, we now have the `info-box` class that can be added to any box

#### InfoItem.vue
- the component has been removed

#### Paginate.vue
- the component has been removed for now, but a new component is in development that will work with the Laravel
pagination

## Upgrade steps

- note that this update also upgrades Laravel to v5.8.x, Vue to v2.6.x, and Horizon to 3.0.x. It is important to take a look at their respective update notes & guides to determine if there any ugrade steps that apply to your project (this is irrespective of Enso)

- remove from `package.json` the following dependencies 
```json
"accounting-js": "^1.1.1",
"bulma-extensions": "^2.2.1",
"chart.js": "^2.7.2",
"chartjs-plugin-datalabels": "^0.3.0",
"css-element-queries": "^1.0.2",
"date-fns": "^2.0.0-alpha.7",
"driver.js": "^0.7.1",
"dropzone": "^5.5.1",
"favico.js": "^0.3.10",
"file-saver": "^1.3.8",
"flag-icon-css": "^3.0.0",
"flatpickr": "^4.5.0",
"highlight.js": "^9.12.0",
"intro.js": "^2.9.3",
"laravel-echo": "^1.4.0",
"lodash": "^4.17.10",
"popper.js": "^1.14.3",
"quill": "^1.3.6",
"textarea-caret": "^3.1.0",
"tiptap": "^0.20.1",
"tiptap-commands": "^0.7.1",
"tiptap-extensions": "^0.23.0",
"tooltip.js": "^1.2.0",
"v-click-outside": "^1.0.5",
"video.js": "^7.0.3",
"vue": "^2.5.16",
"vue-quill-editor": "^3.0.6",
```

- add the following new dependencies to `package.json` (note the new VueJS version)
```json
"@enso-ui/accessories": "^1.0.0",
"@enso-ui/bulma": "^1.0.0",
"@enso-ui/directives": "^1.0.1",
"@enso-ui/forms": "^1.0.1",
"@enso-ui/mixins": "^1.0.0",
"@enso-ui/select": "^1.0.2",
"@enso-ui/tables": "^1.0.0",
"@enso-ui/themes": "^1.0.0",
"@enso-ui/transitions": "^1.0.1",
"@enso-ui/ui": "^1.0.0",
"patch-package": "^6.0.2",
"popper.js": "^1.14.7",
"vue": "^2.6.8",
```

- ensure you have these depenedencies in `package.json` under devDependencies:
```json
"@babel/plugin-syntax-dynamic-import": "^7.2.0",
"@vue/eslint-config-airbnb": "^4.0.0",
"babel-eslint": "^10.0.1",
"cross-env": "^5.1.6",
"eslint": "^5.0.1",
"eslint-plugin-vue": "^5.0.0",
"laravel-mix": "3.0.0",
"node-sass": "^4.11.0",
"webpack-cli": "^3.2.1"
```

- update the `postinstall` section to this:
```json
"postinstall": "opencollective-postinstall && patch-package"
```

- update `composer.json` by removing the following dependencies:
```json
"laravel-enso/addressesmanager": "2.5.*",
"laravel-enso/commentsmanager": "2.4.*",
"laravel-enso/discussions": "1.1.*",
"laravel-enso/documentsmanager": "2.4.*",
"laravel-enso/how-to": "2.2.*",
```

- update `composer.json` by updating the following dependencies:
```json
"laravel-enso/core": "4.0.*",
"laravel/horizon": "^3.0",
"laravel/framework": "5.8.*",
"laravel/telescope": "^2.0",
"sentry/sentry-laravel": "^1.0.0"
```

- remove the following commands from `post-update-cmd`
```json
"php artisan vendor:publish --force --tag=enso-assets",
"php artisan rolcris:update-assets"
```

- sync the `webpack.mix.js` file with the Enso version:
```js
const mix = require('laravel-mix');

mix
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                '@core-routes': `${__dirname}/node_modules/@enso-ui/ui/src/bulma/routes`,
                '@core-pages': `${__dirname}/node_modules/@enso-ui/ui/src/bulma/pages`,
                '@core-middleware': `${__dirname}/node_modules/@enso-ui/ui/src/middleware`,
                '@core-modules': `${__dirname}/node_modules/@enso-ui/ui/src/modules`,
                '@root': `${__dirname}/resources/js`,
                '@pages': `${__dirname}/resources/js/pages`,
                '@store': `${__dirname}/resources/js/store`,
                '@components': `${__dirname}/resources/js/components`,
            },
        },
    })

    .options({
        hmrOptions: {
            port: '3030',
        },
        processCssUrls: false,
        uglify: {
            parallel: true,
            cache: true,
            uglifyOptions: {
                mangle: true,
                compress: true,
            },
        },
    })
    .copyDirectory('resources/images', 'public/images')
    .sass('resources/sass/enso.scss', 'public/css')
    .sass('node_modules/@enso-ui/themes/bulma/light.scss', 'public/themes/light/bulma.min.css')
    .sass('node_modules/@enso-ui/themes/bulma/dark.scss', 'public/themes/dark/bulma.min.css')
    .js('resources/js/enso.js', 'public/js')
    .extract([
        '@fortawesome/fontawesome-free', '@fortawesome/fontawesome-svg-core',
        '@fortawesome/free-brands-svg-icons', '@fortawesome/free-regular-svg-icons',
        '@fortawesome/free-solid-svg-icons', '@fortawesome/vue-fontawesome', 'accounting-js',
        'axios', 'chart.js', 'chartjs-plugin-datalabels', 'css-element-queries', 'date-fns',
        'dropzone', 'file-saver', 'flatpickr', 'laravel-echo', 'lodash', 'popper.js',
        'pusher-js', 'quill', 'raven-js', 'textarea-caret', 'tiptap', 'tiptap-commands',
        'tiptap-extensions', 'tooltip.js', 'v-click-outside', 'v-tooltip', 'vue',
        'vue-quill-editor', 'vue-router', 'vuedraggable', 'vuex',
    ])
    .js('resources/js/tableExample.js', 'public/js')
    .js('resources/js/selectExample.js', 'public/js')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}

```
- The folders below must be kept until you update your app to use the new npm packages. Once you're using the new versions, you may safely delete them:
    - `resources/js/classes/enso`
    - `resources/js/components/enso` 

- delete the following folders since the respective functionality was moved to @enso-ui/ui
    - `resources/js/core`
    - `resources/js/middleware`
    - `resources/js/modules/enso`
    - `resources/js/modules/importers`

- delete the following files since the respective functionality was moved to @enso-ui/themes
    - `resources/sass/extensions.scss`
    - `resources/sass/helpers.scss`
    - `resources/sass/flags.scss`
    - `resources/sass/v-tooltip.scss`

- update - `resources/sass/enso.scss` to match the one from `laravel-enso/enso` repo

- delete the following `resources/js/pages` subfolders (except for customizations!!):
    - `activityLog`,
    - `administration`,
    - `auth`,
    - `dashboard`,
    - `dataimport`,
    - `files`,
    - `how-to`,
    - `notifications`,
    - `system`

- delete the following `resources/js/routes` subfolders (except for customizations!!):
    - `administration`,
    - `system`,
    - `activityLog.js`,
    - `administration.js`,
    - `auth.js`,
    - `dashboard.js`,
    - `dataImport.js`,
    - `files.js`,
    - `howToVideos.js`,
    - `notFound.js`,
    - `notifications.js`,
    - `system.js`,
    - `unauthorized.js`

- delete the following `resources/js/store` subfolders:
    - `layout`,
    - `auth.js`,
    - `bookmarks.js`,
    - `layout.js`,
    - `localisation`,
    - `menus.js`,
    - `preferences.js`

- update the following files from the `resources/js` folder:
    - `enso.js`
    - `router.js`
    - `selectExample.js`
    - `store.js`
    - `tableExample.js`

- `composer install && composer update`

- `php artisan enso:upgrade`

- run `php artisan horizon:install`

- republish the resources: `php artisan vendor:publish --tag=horizon-assets --force`

- update the Horizon `gate()` method in the published `HorizonApplicationServiceProvider` to:
```php
{
    Gate::define('viewHorizon', function ($user) {
        return auth()->check() && $user->isAdmin();
    });
}
```

- if necessary, update `App\Exceptions\Handler` to include the new sentry reporting syntax

- update the sentry config and remove the `user_context` option which is now deprecated

- `yarn install && yarn upgrade`

- `canAccess` must be injected where it is used

- if you have customized Enso, use `patch-package` to reapply those customizations for each individual package. Note that when using yarn, the [postinstall-postinstall](https://github.com/ds300/patch-package#why-use-postinstall-postinstall-with-yarn) package is also needed.

- build

- replace all relative RouterView imports such as `const RouterView = () => import('../../../core/Router.vue');` with the package import `const RouterView = () => import('@core-pages/Router.vue');`

- replace relative routeImporer imports such as: `import routeImporter from '../modules/importers/routeImporter;` with the package import : `import routeImporter from '@core-modules/importers/routeImporter';`

- replace relative date-fns imports such as: `import format from '../../../modules/enso/plugins/date-fns/format;` with the package import `import format from '@core-modules/plugins/date-fns/format';` (notably `format`, `formatDistance`) 

- comment out the import of the `tiptap-extensions` from `/resources/js/components/enso/vueforms/Wysiwyg.vue`

- in `resources/images` ensure you have `earthlink.svg` & `enso-favicon.png`

- when adding your own local routes that overwrite or reuse part or the core routes, ensure that 
within the route file you expose only the `path` and the `children` properties. If you also set the `meta` property, that would result in having the core routes overwritten by your local routes vs having the local routes merged into the core routes.

- move the icon imports from `resources/js/core/structure/menu/icons/app.js` to `resources/js/app.js`

- Migrate all pages/components by looking at the [New Packages section](#new-packages).

- inject `i18n` and `errorHandler` everywhere they're used

## 2.16.3
Small bug fixes, composer & npm packages updates

## 2.16.2

### Improvements
- adds a new system for collection missing translation keys, optimizing the number of needed server requests
- moves the IO logic in its own package, `laravel-enso/io`
- refactors / improves the IO ui
- refactors DataImport / DataExport to make use of the new helpers available for IO Operations
- removes the chunk option from `config/enso/datatable.php`. The chunk is determined dinamically now
- fixes missing header bug in VueDatatable excel exports, on every sheet
- fixes exporting hidden columns (!visible) in vue datatable
- fixes icon rendering bug in menu manager table when using fa icons with other prefix than `fas`
- updates all Enso's tables to use the new cache feature

### To upgrade
- run `composer update`
- run `yarn run dev`

## 2.16.1
The public assets were removed from the git repo.

If you would like the same approach for your own project match the .gitignore file with the one from the main repo (the public folder in particular) and make a commit with the new file.

After run `git rm -r --cached ./`, perform a second commit and that's it.

Beware that if you do that you will have to compile the assets in the deployment process.

## 2.16.0
Starting with this version Enso gets native multitenancy support, 
thanks to the our newest package [laravel-enso/multitenancy](https://github.com/laravel-enso/multitenancy)

### Improvements
- for more information on multitenancy, take a look at the documentation
- adds caching support for totals in tables; this can be have a great positive impact in large tables. 
For more info check the [docs](https://docs.laravel-enso.com/packages/vue-datatable.html#caching-support)
- strengthens the user -> person -> company -> userGroup -> roles policies, getting ready for MT :D
- upgrades horizon to 2.0.x

### Bug Fixes
- fixes a bug in vue select when the selected value is (int) 0
- fixes a bug in typeahead when using the filter function. This bug affected the searchable functionality
- other minor bug fixes

### Upgrade steps:
- update in `composer.json`: `"laravel-enso/core": "3.7.*"`
- update your `database.php` config file and make sure that you have a `system` connection  that is also configured as default and an optionally `tenant` connection.  Use the file from the main repository as an example.
- run `composer update`
- run `php artisan enso:upgrade`
- update `CompanyFactory` with the one from the demo repository
- update `.env.example` && `.env.testing` if needed
- update the `horizon.php` & `datatable.php` configuration files with the ones from the main repository

## 2.15.3

### Improvements
- improves `vue-table` BE filtering
- makes `fullInfoRecordInfo` available in `vue-table` templates
- refreshes the table example page
- allows `Obj` properties to contain dash
- updates the default values in `config/enso/auth.php`
- makes the `position` field require a company to be selected, in People
- adds FE permissions check for company people management
- upgrades the filters (`vue-filter`, `vue-select-filter`, `date-filter`, `date-interval-filter`, `interval-filter`)
    - adds a `compact` prop
    - makes the filters of the same height in both compact and non compact mode
    - allows stacking of more filters horizontally
    - adds an `all` option for DateFilter
    - changes selection in `vue-filter` to `warning`

### Bug Fixes
- fixes filtering reporting bug in `vue-table`'s ui
- fixes a bug when using `fullInfoRecordInfo`
- fixes `money` format in `vue-table`
- fixes the `.is-naked` helper class for `.button`

### Upgrade instructions
- `composer update`
- `yarn upgrade`
- `yarn run dev`

## 2.15.2

### Changes
Starting now Enso supports custom password policies via config.

You have the option to configure the password's:
    - lifetime (in days)
    - length
    - min upper case letters
    - min numeric characters
    - min special characters
    - max login attempts per minute

If you leave the lifetime null or 0 the user will never be required to change his password. 

Once the lifetime is configured the user will receive notifications upon login when he has less than 3 days until his current password becomes invalid.

### Bug Fixes
- fixes a bug when clearing unread notifications
- fixes a warning in `<password-strength>`

### Upgrade instructions
- run `composer update`
- run `php artisan enso:upgrade`
- publish the core configs if you want to customize the auth params
- configure in `.env` your desired password params -> see `config/enso/auth.php` for the available options

## 2.15.1
In this version we focused on greatly improving the assets compiling process. With the new configuration build times dropped by ~ 75% for production.

### Changes

#### General
- improves webpack.mix.js
    - enables use of multiple cores
    - disables [processCssUrls](https://laravel.com/docs/5.7/mix#url-processing)
    - enables [vender extraction](https://laravel.com/docs/5.7/mix#vendor-extraction)
- includes manifest.js and vendor.js in index.blade.php to support vendor extraction
- updates in all the js file (with the new linter / rules)

#### Core
- moves UserGroup pages to the publishing list
- adds AR and PT languages support in local date-fns i18n
- adds `manifest.json` and `vendor.js` to `index.blade.php` to make use of webpack's vendor extraction

#### Charts
- renames `get()` => `fetch()` in `<chart-card>`

#### Addresses, Comments, Documents, Discussions, CompanyPeople
- renames `get()` => `fetch()` in `<*-card>` wrapper

### Bugs
- fixes `<address-card>` refresh (`get` => `fetch`)

### Upgrade instructions
- update in `composer.json`: `"laravel-enso/core": "3.6.*"`
- run composer update
- delete your local `node_modules`, `yarn.lock`, `package-lock.json`
- update the following local files with the ones from the main repo
    - `webpack.mix.js`
    - `.eslintrc.js`
    - `package.json`
    - `.babelrc`
- run `yarn`
- compile
- run in tinker the following command:
    - `LaravelEnso\PermissionManager\app\Models\Permission::where('name', 'LIKE', 'administration.companies.contacts.%')->delete();` (it was a typo in the `enso:upgrade` command)

## 2.15.0

### Changes
This version aimed to allow direct association of users to companies via persons.
This will provide a good structure for creating users that will belong to different organisations (clients for instance)
and the related restrictions when accessing application's resources.

The old contacts structure is deprecated. The associated people are now called `company people`.

An upgrade command is provided that takes care of all the database structural updates and transforms the old
contacts / mandataries to company people.

#### Addresses, Comments, Discussions, Documents
- refactors the count update tracking

#### Companies
- removes deprecated command
- removes the deprecated contact request validation (including customization)
- removes `mandatary_position` from the table / form
- reorganizes the company form
- restricts the `mandatary` to the list of associated people
- creates the `adminstration.companies.people` structure
- limits the company selection when using the `CompanySelectController` for users that do not belong to `AdminGroup`
- reuses the `PersonSelectController` for company people
- updates the `people()` relation in `Company.php`
- removes the deprecated `contactFormTemplate` from `companies.php` config file
- updates the `CompanyFactory`
- removes the deprecated `ContactFactory`
- replaces the deprecated `/resources/js/components/enso/contacts/` components with `/resources/js/components/enso/companies/`
- updates the implementation of `accessories` in the `Edit.vue` page
- updates the routes
- replaces `ContactTest` with `CompanyPeopleTest`

#### Core
- updates the `Upgrade` command to take care of 3.3.* => ^3.4 upgrades
- refactors the role change authorization in `UserController`
- adds the `AdminGroupId` constant to the `UserGroup` model
- adds the `belongsToAdminGroup()` helper to the `User` model
- refines the user delete error messages
- updates the `UserSeeder`
- fixes a bug when having more than 2 level nested menus

#### Discussions
- fixes discussion delete

#### DataImport
- fixes cell sanitization
- fixes header normalization
- fixes tests

#### FormBuilder
- adds selected tab name to `_params` on submit
- fixes modal visibility in an edge case

#### People
- adds `PersonPolicy`
- adds `appellative` & `position` to `PersonSelectControllers`'s query attributes
- drops the `gender` column and adds a `gender()` helper that determines the gender by the `title` value; Adds a `isMandatary()` helper
- adds a `company()` relation to the `Person` model
- makes the form tabbed; adds `company_id` and `position` to the table / form
- updates the `PersonFactory`
- updates the implementation of `accessories` in the `Edit.vue` page
- updates the tests

#### Select
- fetches the option list when clicking on the `No options` label
- adds `cursor:pointer` to the `.select-value` class
- makes the request object (optional) available for the select controllers

#### StructureManager
- updates the stubs for select controllers and form pages

#### TrackWho
- adds return type to relations. This will help when requesting the relations in contracts

#### VueComponents
- refactors `accessories`; now it can use any kind of accessory
- updates `tabs` to emit the `selected` event when mounted

### Upgrade instructions - estimated time per project ~ 5min
- update in `composer.json`: "laravel-enso/core": "3.5.*"
- run `composer update`
- run `php artisan migrate`
- run `php artisan enso:upgrade`
- update `CompanyFactory` and `PersonFactory` and `UserSeeder` with the ones from the packages
- remove `ContactFactory`
- remove `/resources/js/components/enso/contacts/`
- remove `Contact` from `config/enso/searchable.php`

Note:
If you customized the company / person form / table / pages you will have to manually update them accordingly...

## 2.14.1

### Bug Fixes
- fixes header normalization in Data Import
- fixes the role configurator
- adds the missing searchOperator from datatable config that was breaking the table search
- fixes tabs position in formbuilder to properly display select's dropdown
- updates structure manager's from create / edit stubs for `enso-form`
- removes a redundant test in Searchable that was causing problems on certain env's
- prevents form submisison when used in a form for `file.vue`

### Improvements
- adds an exception on export when another export is running for the same user / table
- adds a `compact` prop to `vue-filter`
- adds translations for the tutorial navigation buttons

### Note
The Documentation is up to date now

## 2.14.0

### Changes
This is one of the biggest releases until now, with changes such as the complete rewrite of the Data Import package, the rewrite and improvement of the DataTable Export functionality, enhancements and new functionality for the VueForm and much more.
The improvements brought to the DataTable Export and the DataImport now allow the export of practically unlimited records from a datatable, as well as the import of xlsx files limited only by your ability to create them and the limitations of the file format.
That's not all though, as the new mechanisms also become much much more efficient due to the parallelization of the operations. This is made possible by leveraging Laravel's queueing facilities and impressive load balancing.
Together with the refreshed web socketed operations' progress reporting we now have hugely powerful, high throughput solutions for I/O operations.
This is yet another step towards our vision for Enso and rest assured, there is more to come.

#### Core
- adds IO structure for monitoring background export/import operations progress in real-time (needs websockets). It will soon be refactored and extracted to its own separate package
- fixes the global search on medium screen sized devices
- refactors the request management helpers
- adds params support for translations, thanks to @jlsjonas
- fixes translation for the `auth` layout
- adds a `shortNumber` filter ( `{{ 1000 | shortNumber }} // 1k`)
- globally adds the `font-awesome-layers` component under the name of `fal`
- both the `dataimport` and `companies` packages are now required by `core` and are no longer optional

#### DataExport
- adds `type`, `entries`, `status` and `created_by` columns to the table
- uses the `CreatedBy` trait
- integrates with the new background operations management system (IO) for real time progress in the FE

#### DataImport
- completely redesigned the package and import philosophy. Adds support for a virtually infinite number of rows that can imported in the same import with very low memory consumption, by splitting the import file in chunks and processing them in queued jobs
- adds queue management
- generates a downloadable xlsx rejected rows summary if there are import validation errors. The summary has the same structure as the import file with an extra column (on each sheet) that will describe all the validation errors for each row. This will streamline the import operations when dealing with large files
- improves local (dev's) import and validation classes
- adds `before` and `after` hooks which are available in the importing process
- integrates with the new background operations management system (IO) for real time progress in the FE
- comes with a new `import-uploader` component that can be placed anywhere in the applications for local imports

#### Helpers
- enhances the `Obj` class. Now it can receive any object or associative array, including Laravel collections, and even Models with loaded relations. 
- changed the `Obj@get($key)` method to return `null` for an unset `$key`, instead of throwing an exception
- enhances the Enum by reordering the data source priority. Take a look at `LaravelEnso\Examples\app\Enums\SeniorityEnum` for an example

#### FormBuilder
- adds the `tabs` property to the form template root. If provided, it will require a `tab` property for each section, which will represent the tab's label. The sections will be grouped in tabs using their given labels
- the delete modal has the commit button focused by default
- exposes a series of getters/helpers from `vue-from` and `enso-form` that can be accessed via `$refs`:
    - computed:
        - `data` - represents the whole object
        - `customFields` - represents  custom fields array
        - `errors` - represents the errors object
    - methods
        - `formData()` - returns the params that are sent to the server on POST / PATCH
        - `field(field)` - returns the field object for a given label
        - `param(param)` - returns the specified param from the optional `params` object
        - `routeParam(param)` - returns the route param from the `routeParams` object
        - `fetch()` - form's fetch method for getting the template

#### VueComponents
- removes the deprecated `MorpableContainer`
- adds a new `enso-tabs` component. The old `tabs` component is visually limited to the Bulma available options. `enso-tabs` has less props and displays the custom tabs used in the Files menu, the `accesories` component and in `vue-form` (new!)
- adds compact mode to `accesories`

#### VueDatatable
- adds practically unlimited excel export functionality by greatly improving the table fetcher. The whole process is done in the background and uses a small amount of memory. You should consider using appropriate values for the configuration options `enso.datatable.exports.timeout` and for the `queues.connections.yourConnection.retry_after`
- improves the builder logic and reusability
- optimizes the Builder for date / enum / translatable computing
- renamed the `translation` template param to `translatable`
- the (delete) modal accepts `shift-enter` as a keyboard shortcut for the commit action
- changes the way actions are processed. Now, in the `CustomAction@process` class / method only one `$row` at a time will be available
- adds options for customizing the export & notification queues
- integrates with the new background operations management system (IO) for real time progress in the FE

### Upgrade instructions - estimated time per project ~ 10-15min
- update in `composer.json`: "laravel-enso/core": "3.4.*"
- from `composer.json`, remove the packages:
    * "laravel-enso/companies"
    * "laravel-enso/dataimport"
- run `composer update`
- run `php artisan migrate`
- run `yarn upgrade`
- run `yarn run dev`
- run `php artisan enso:upgrade`
- add the new route in your `routes/channels.php` file
    ```php
    Broadcast::channel('operations.{userId}', function ($user, $operationId) {
        return (int) $user->id === (int) $operationId;
    });
    ```
- in your `config/queue.php` file make sure that you set `retry_after` to a higher value for the imports / exports connection (we are using 1800)
- update the configuration files with the newly introduced options:
    * `config/enso/imports.php` with `vendor/laravel-enso/dataimport/src/config/imports.php`
    * `config/enso/datatable.php` with `vendor/laravel-enso/vuedatatable/src/config/datatable.php`
- configure your `config/horizon.php` to make sure that you have the appropriate queues for both local and production envs. You can use the config from this repo as an example.
- refactor your import classes and validators to match the new structure
- if necessary, in your table templates, rename the attribute `translation` to `translatable` 
- sync `phpunit.xml` with the one from the main repo

## 2.13.17

- fixes small bugs
- upgrades date-fns to the latest alpha

To upgrade existing projects:

- run `composer update`
- update in your package.json `"date-fns": "^2.0.0-alpha.7"`
- run `yarn install`
- update in `config/enso/config.php` `jsDateFormat => 'dd-MM-yyyy'`
- read [this](https://github.com/date-fns/date-fns/blob/master/docs/unicodeTokens.md)
- update in your code the date format [accordingly](https://date-fns.org/v2.0.0-alpha.26/docs/format)

## 2.13.16

### Changes and upgrade instructions:

#### General
- renames `initialised` to `ready` in `Auth.vue`, `activityLog/Index.vue`, 'teams/Index.vue`. If you were using this flag be sure to update your local code.

#### VueDatatable
- adds label and tooltip support for row buttons
- renames `getData()` to `fetch()`. If you were calling `this.$refs.table.getData()` in the code be sure to rename it

#### FormBuilder
- adds template fetching logic VueForm similar to VueTable
- adds EnsoForm for using VueForm in the Enso environment. VueFormSS is be deprecated and must be replaced with EnsoForm. For existing projects you can safely search & replace all instances of `vue-form-ss` with `enso-form` and `VueFormSS` with `EnsoForm`
- adds & refactors field helpers in VueForm / EnsoForm:
    - `field(value)`
    - `param(param)`
    - `routeParam(param)`
    - `customFields` - computed property

To upgrade besides the above notes you must manually bump the core version in your composer.json:
    - "laravel-enso/core": "3.3.*"

Run `composer update`, `yarn upgrade`, compile and enjoy





## 2.13.15
- fixes the default spa redirect to allow Laravel routes caching see [#169](https://github.com/laravel-enso/Enso/issues/169)

Upgrade steps:

- update `telescope.php` with the one provided in the project
- in `web.php` file change the SPA redirect from

```php
Route::get('/{any}', function () {
    return view('laravel-enso/core::index');
})->where('any', '.*');
```

to 

```php
Route::view('/{any}', 'laravel-enso/core::index')
    ->where('any', '.*');
```

## 2.13.14
- upgrades telescope to 1.x
- minor improvements
- minor bug fixes

## 2.13.13
- added support for using hasMany relations to determine selected values in the formbuilder
- dropped the `roleList()` helper from the `HasRoles` trait (as a consequense of the above)
- adds discussions tests
- adds ModalCard.vue
- fixes many small bugs

For existing projects publish the `discussions-factory` and add the test suite to phpunit.xml, if applicable

## 2.13.12
- added wysiwyg field to vue-select based on tiptap
- upgrades telescope, less bugs

For existing projects:

- run `yarn add tiptap tiptap-commands tiptap-extensions`
- compile, including enso's light & dark themes
- update in telescope.php `Watchers\RequestWatcher::class => env('TELESCOPE_REQUEST_WATCHER', true),`

## 2.13.11
- from now on Enso ships with only two themes, a light one and a dark one. If you enjoyed bulmaswatch it's pretty easy to keep the old functionality in place, you just have to customize the `themes.php` config file, the `themeSelector.vue` component and the local `webpack.mix.js`
- the vue select was improved visually and extended with a `readonly` prop
- lots of minor fixes

Upgrade steps:
- run `composer update; yarn upgrade`
- run `art vendor:publish --tag=enso-preferences --force`
- manually overwrite the `config/enso/themes.php` config file with the one provided by the core package
- update `webpack.min.js` accordingly - you can use the one from this repo
- run `php artisan enso:preferences:clear`
- compile & enjoy (you should compile the themes at least the first time -> uncomment the two lines from webpack.js)

## 2.13.10
- improved searchable; added tests
- added `nullLast` sorting option to vuedatatable
- fixed a bug in vueform when using a different id key in edited models
- improves the menu behaviour (auto toggle on parent menus)

For existing projects check the repo's `phpunit.xml` file and make sure you have all the test suites for your instance.

Note: temporarily disable the `RequestWatcher` in telescope.php (`Watchers\RequestWatcher::class => env('TELESCOPE_REQUEST_WATCHER', false)`) until the problem will be fixed -> https://github.com/laravel/telescope/pull/357

## 2.13.9
- adds categories filtering for searchable

## 2.13.8
- fixes searchable with multiple words
- improves menu behavior laravel-enso/core#80
- adds telescope back for local. If you need it in production be sure that you have it in the right section in composer.json

## 2.13.7
- adds tests for people / company / company contacts
- fixes bugs

Note: For existing projects replace the old ContactFactory with the one provided by the Companies package.

## 2.13.6
- adds comments to company's edit form
- updates `Dropdown.vue`, now it uses `em` instead of `px` for sizing
- improves all filters
- improves toastr
- refactors vuecomponents

## 2.13.5
- major FE refactor for `vue-forms`. Docs update soon but don't worry, should be backward compatible
- lots of minor bug fixes

## 2.13.4
- now the contacts structure is a part of companies. The old package will be no longer maintained. If you have already contacts make sure that you migrate them before upgrading...

Steps for upgrade:

- `composer remove laravel-enso/contacts`
- update in componse.json `"laravel-enso/companies": "1.1.*",`
- `composer update`
- `php artisan migrate`
- `rm config/enso/contacts.php`
- remove the `Contacts` trait from `App\User`

## 2.13.3
- adds Telescope and drops debugbar
- adds `Uploads` section to the `Files` menu
- small improvements to the `Files` menu
- adds a `storageLimit` option in the `enso/files.php` config
- droppes deprecated columns

Upgrade an existing project:
  - `composer require laravel/telescope`
  - remove `"barryvdh/laravel-debugbar"` from `require-dev` in your composer (optional)
  - `composer update`
  - `php artisan telescope:install`
  - `php artisan migrate`
  - `php artisan enso:upgrade`
  - if used, remove `\Debugbar::disable();` from your `AppServiceProvider`'s `boot` method
  - Add `App\Providers\TelescopeServiceProvider::class,` to the Application Service Providers in `config/app.php`
  
  Please also note that telescope has a dependency on the `ext-bcmath` php extension

## 2.13.2

- improved activityLog. For existing projects **update** the syntax in the `protected $loggable` property for relations from `[Model::class, $attribute]` to `[Model::class => $attribute]` if any local models are using that. Adds optional `$loggedEvents`
- improves searchable
- adds `fixed-width` for menu icons
- fixes some UI bugs

## 2.13.1
- minor bug fixes
- improves search to work with model relations
- adds `DateFilter.vue` - implemented in Files menu

## 2.13.0

### General
This version greatly improves the permission management system by simplifiyng parts of the core structure and generally allowing for more flexibility.

It also adds a set of changes that widen convention based matchings (for routes, parameters, models, etc.) while reducing a significant amount of lines of code and improving readability.

The permissions table provides all the needed information when checking user access for both routes and menus. That said, the `menu_role` pivot table was dropped as it's no longer needed. The menus section from the role configurator was also dropped and the UI streamlined.

The menu table was improved by replacing the `link` column, that was previously string and stored the permission name, with `permission_id` (nullable for parent menus).

The `Permission Groups` were also dropped (menu, table, relation to permissions). The grouping for permission is determined dynamically based on the route name prefixes. This permits  any type of nested routes structure and removes the previous restriction of having to provide the same number of depth levels for all the route subgroups. Now we can have `administration.companies.routeNames` and also `administration.companies.contacts.routeName` and the improved interface will handle this structure.

The frontend routing was adjusted slightly to better match the backend routing, in the sense that all the route parameters are now named after the model instead of the generic `id`  (for instance, `adminisration/users/:id/edit` became `administration/users/:user/edit`) similar to Laravel conventions.

Read all the notes below before jumping to the `Upgrade steps`. 

### AddressManager
- removed the `requestValidator` property from `addresses.php` config file (you may remove this from your local config)
- to provide your own request validator create a class that implements the `ValidatesAddressRequest` contract and update the binding to the contract into a service provider

    ```php
    $this->app->bind(
        ValidatesAddressRequest::class, MyAddressRequestValidator::class
    );
    ```

### Charts
- added i18n to `ChartCart.vue`. Now the labels, legends and data series are translated.
- added `fillBackgroundOpacity` in the `charts.php` config file.
- rearranged the default color order.

### CommentsManager
- refactored the back-end
- to provide your own, custom, `CommentTagNotification`, create a notification that implements the `NotifiesTaggedUsers` contract and update the binding to the contract into a service provider

    ```php
    $this->app->bind(
        NotifiesTaggedUsers::class, MyCustomNotification::class
    );
    ```

### Companies
- removed the `requestValidator` property from `companies.php` config file (you may remove this from your local config)
- to provide your own request validator create a class that implements the `ValidatesCompanyRequest` contract and update the binding to the contract into a service provider

    ```php
    $this->app->bind(
        ValidatesCompanyRequest::class, MyCompanyRequestValidator::class
    );
    ```

### Core
- removed deprecated commands (`enso:update`, `enso:track-who:update`, `enso:filemanager:upgrade`)
- renamed `enso:clear-preferences` to `enso:preferences:clear`
- renamed `enso:update-global-preferences` to `enso:preferences:update-global`
- added an `enso:upgrade` command for v2.13.x - update forms, pages, tables and structure migrations
- replaced `implicitMenu` with `implicitRoute` in the store
- renamed the `core.index` route to `core.home.index`
- refactored the frontend sidebar / menu / menuItem Vue components

### FormBuilder
- now you can disable the back button through the `actions` helper
- refactored action computing
- added a `routeParams` bag useful for nested routes or custom contexts. From now on, the model's id will be available here in the edit forms
- updated the `vue-form` component to work with model matched route params / segments
- improved the `vue-form-ss` component by adding default `routeParams`

### MenuManager
- removed the `link` column and added `permission_id` from the `menus` table
- updated the forms, pages, table and the structure migration
- refactored the menu tree generation using the `permissions` table instead of the deprecated `role_menu` table 
- updated the factory

### People
- removed the `requestValidator` property from the `people.php` config file (you may remove this from your local config)
- to provide your own request validator create a class that implements the `ValidatesPersonRequest` contract and update the binding to the contract into a service provider

    ```php
    $this->app->bind(
        ValidatesPersonRequest::class, MyPersonRequestValidator::class
    );
    ```

### PermissionManager
- dropped the deprecated `PermissionGroup` structure
- dropped support for `ResourcePermissions`
- updated the forms, pages, table and the structure migration

### RoleManager
- dropped the `menus` relationship from the Role model

### StructureMigration, CLI
- removed the deprecated `PermissionGroup` creation / support. Now the `permissionGroup` option is used only for the route prefix. This resulted in a substantial code cleanup for both the CLI and StructureMigration
- renamed `link` to `route` in the array menu definition
- updated the table writer with the new template changes - updates `getTableData` route to `tableData`
- updated the crud controller to generate the `'param' => ['model' => $model->id]` respose on storing new models
- removed `:route-params=....` from the create and edit page stubs
- updated the FE routes generation to use the model name param insteand of `id`
- added the option to generate the assets without performing validation

### VueComponents
- added a `keep-alive` boolean prop for `Tab.vue`

### VueDatatable
- `readSuffix` was renamed to `dataRouteSuffix` and it's now optional, being defined globally in the `datatables.php` config file, with a default value of `tableData`. If needed, you may overwrite it per each table template 
- added a 'highlight' option to the config that defaults to 'has-background-info',

### Upgrading existing projects

#### Important Notes
- make sure you read all of the above.
- the upgrade steps presented below will work only for Enso **v2.12.8**. If you are not already at this version, make sure that you upgrade to v2.12.8 before jumping to v2.13.x

#### Upgrade steps
- update in your `composer.json`: "laravel-enso/core": "3.2.*",
- run `composer update`
- run `php artisan vendor:publish --tag=menus-factory --force
- run `php artisan vendor:publish --tag=roles-seeder --force
- run `php artisan enso:upgrade`

**search & replace**

- search the whole project for `protected $permissionGroup` and remove the property from all structure migrations (optional)
- search for `getTableData` and replace it with `tableData` in all the route files and structure migrations
- search for `readSuffix` in your table templates and remove it if you want to use the global `dataRouteSuffix` from the config or replace it with `dataRouteSuffix` if you need a custom one
- add the `'dataRouteSuffix' => 'tableData',` line in your `config/enso/datatable.php` config file
- search for `'link' => '` and replace it with `'route' => '` in all your structure migrations
- search for `'id' => $` in all your crud controllers (store() methods) and update the returned array from
`'id' => $model->id,` to `'param' => ['model' => $model->id],` where `model` will correspond to the according path segment (model name)
- search for `:id` in all your frontend routes and replace `id` with `model` where model is the actual camelCase model, matched with the one from the backend route.
- search for `:route-params="[$route.name, null, false]"` in your 'Create' pages and remove it. Now the vue-form-ss defaults to this. (optional)
- search for `:route-params="[$route.name, $route.params.id, false]"` in your edit pages and remove it. (optional)


## 2.12.8
- mostly a BE refactor
- some deprecated classes where removed
- minor bugs fixes
- minor new improvements
- dependencies were updated

UPGRADE:

make sure that `config/form.php` contains the new `back` button. Look here for the format - `vendor/laravel-enso/formbuilder/src/config/forms.php`

## 2.12.7

### Important
- updated `QUEUE_DRIVER` key to `QUEUE_CONNECTION` in `config/queue.php`
- updated `SENTRY_DSN` to `SENTRY_LARAVEL_DSN` in `config/sentry.php`

### Fixes & improvements
- added a `readonly` boolean prop to `vue-filter`
- fix: removes the first (default) `selected` emitted event in tabs
- added `highlight(dtRowId)` & `clearHighlighted()` helpers to the FE `vue-table` api
- improved `vue-form-ss` to use the `params` prop in the template fetching reuqest
- fixed publsih tag in companies

## 2.12.6
- vuedatatable's `ModelTable` class now provides a `templatePath()` method
- added configs for customizations in people and companies
- fixed bugs in impersonate and teams
- improved the customization for addresses
- updated composer & npm deps

To update an existing project change in `composer.json`: "laravel-enso/addressesmanager": "2.5.*"

## 2.12.5
- improved menu's animations
- added a `compact` option for documents manager where files can be seen in the old format
- some minor bug fixes

## 2.12.4
- added delete restrains/cascading options for the morphable packages - Addresses, Comments, Contacts, Discussions and Documents. To make use of the add in the local configs `'onDelete' => 'type'` where type can be `cascade` or `restrict`
- improved the bookmarks, now we can unstick the first bookmark when it's single
- fixed a bug in select, now the selection can be deselected in single mode
- hided the vue select dropdown's scrollbar 
- added missing exception import in companies / people models
- hided the menu's scroll bar
- added scroll to settings

## 2.12.3
- bug fixes for v2.12.x

## 2.12.2
- improved the factories & seeders. `PersonSeeder` was removed
- improves comment and user tests

## 2.12.1
- adds `has-background-light` on most of the focusable elements in pages
- updates fonts for the *clean* theme

## 2.12.0

Facing our latest challenges we decided to change the way users are handled in Enso by introducing a new structure of *people*. This was the main objective of this upgrade.

Some of the people will be users (all users are people), some can be contacts (soon), some can be clients or employees, and some can be of all types at once. This structure will handle all the personal information for persons that are relevant to the app.

The second objective was cleaning up and improving the code, trying to make everything simpler and more consistent. We tried to rename what was was confusing, to move logic to where it belongs and so on. 

The majority of tests were also improved.

### Upgrade steps
For existing projects we created an upgrade command that will take care of most of the changes, like migrating the current user table into two tables, users & people, and so on.

Make sure you have a backup of your database before starting. If you find any problems use the issue tracker to let us know.

- update `composer.json` dependencies:
    - "laravel-enso/addressesmanager": "2.4.*",
    - "laravel-enso/commentsmanager": "2.4.*",
    - "laravel-enso/companies": "1.0.*",
    - "laravel-enso/contacts": "2.3.*",
    - "laravel-enso/dataimport": "2.5.*",
    - "laravel-enso/discussions": "1.1.*",
    - "laravel-enso/documentsmanager": "2.4.*",
    - "laravel-enso/how-to": "2.2.*",
    - "laravel-enso/teams": "1.0.*",
- run `composer update`
- run `php artisan enso:upgrade`
- run `php artisan migrate`
- run `php artisan enso:upgrade` (again, don't ask :) )
- run `php artisan enso:update-global-preferences`
- run `php artisan enso:track-who:update`
- read the individual package changes below, and where you find upgrade instructions follow those steps.
- run `yarn dev` / `npm run dev`

Note: The upgrade command will do the following:
- rename migrations to match the new names (for example the owner related migrations)
- rename the `owners` & `owner_role` tables and relevant relation columns
- prepares the `users` table to work with `people`
- splits users data into `users` and `people`
- removes all the deprecated permissions and renames one that were changed (`selectOptions` => `options` and so on)
- updates the menus to reflect the new structures;

### ActionLogger
#### Changes
- renamed the `HasActionLogs` trait to `ActionLogs`
- improved test

#### Upgrade
This change should not affect existing projects.

### ActivityLog
#### Changes
- renamed the `LogActivity` trait to `LogsActivity`
- the logger will not attempt to persist data when there is no authenticated user - this was causing problems when using seeder / playing in tinker etc

#### Upgrade
Search in the whole project for `LogActivity` and replace it with `LogsActivity`.

### AddressesManager
#### Changes
- **changed how the morphable relation is handled** <a id="morphable"></a>
- the front-end component works now takes the destination model's *classname* instead of an alias
-  the `addressable_type` and `addressable_id` are dynamically assigned in the form object inside the `AddressForm` component
- the index request is now validated and helps the dev properly configure the front-end component
- as a result of these changes:
    - the back-end logic is more natural without having to create a custom store method
    - there is no need for the `addressable` key from the `addresses.php` config, it can now be removed
    - the `ConfigMapper` was deprecated and removed

- an observer was added that will automatically set the first added address as the default
- the `countriesSelectOptions` route/permission was renamed to `countryOptions`

#### Upgrade
To upgrade, manually replace the provided `type` property where the front-end component is used, replacing the old alias with the mapped class from the `addresses.php` config. Afterwards, remove the `addressable` key from the config. (see [this change](https://github.com/laravel-enso/Enso/commit/be5743c80bf028468ae5dbf30956b5c87d30c634#diff-c55ea2ae73bcf4f5d68ad75da15d5546) for an example)

### AvatarManager
#### Changes
- improved tests

#### ToDo
- this package needs a refactor to exclude the edge cases where users don't get a generated avatar and the front-end breaks

### CommentsManager
#### Changes
- upgraded the way that the *morphable* relation is handled similar to [AddressManager](#morphable) was changed
- added a publishable `CommentFactory`
- improved validation
- dropped the `TaggableUsers` structure (controller, response, route/permission) in favour of the core's `UserSelectController`
- improved the tests

#### Upgrade
The upgrade steps are similar to [AddressManager](#morphable)

### Companies (*New*)
#### Features
- this package will handle company management in Enso
- full crud / menu
- addresses, contacts, documents, discussions and comments
- provides a publishable `CompanyFactory`

#### Installation
It comes by default in an fresh Enso. To install it in an existing project:
 - `composer require laravel-enso/companies`
 - `php artisan migrate`
 - `php artisan vendor:publish --tag=companies-assets`
 - `yarn dev` / `npm run dev`
 - `php artisan vendor:publish --tag=companies-factory` (optional)
 - `factory(LaravelEnso\Companies\app\Models\Company::class, xx)->create()` (optional)

#### ToDo
Plans for the near future: 
- extend it to support customizations through publishable form and table templates
- customize validation rules through the config
- add a dedicated *contacts* structure based on `People`

### Contacts
#### Changes
- upgraded the way that the *morphable* relation is handled similar to [AddressManager](#morphable) was changed
- improved the `ContactFactory`
- improved the request validations
- improved tests
- changed the form icon to a more relavant one

#### Upgrade
The upgrade steps are similar to [AddressManager](#morphable)

### Core
#### Changes
- transformed / renamed the old `Owner` into `UserGroup`
- dropped personal information from the users table, except the email needed for login
- integrated the `User` with the new `Person`
- `selectOptions` routes were renamed to `options`
- added missing `themes` config in `AppServiceProvider`
- moved `teams` in a dedicated package
- updated back-end & front-end to work with the new `people` structure
- added `user_id` to fillable in `Preference`
- improved user policy
- removed the `ownerModel` from config
- improved `UserFactory`
- added publishable `UserGroupFactory`
- added a default order for `Dashboard`, `Administration`, `System`, `Users` & `UserGroups` menus
- renamed *historyTabs* into *bookmarks* and enhanced the store logic
- droped for the moment support for the `Touch` layout. If it will be of any interest in the future we can look again in this direction
- added an `enums` key in vuex store
- replaced the use of deprecated `$bus` with `$root` for vue events
- refactord the notifications front-end logic
- adds translation to typeahead labels in `Search.vue`
- improved tests
- **added the `Upgrade` command**

#### Upgrade
- replace the use of `Owner` with `UserGroup` if the case
- replace `this.$bus` with `this.$root` where you emit/listen to events
- remove the `ownerModel` key from `enso/config.php`

### DataExport
- makes the `created_by` nullable

### DataImport
- changed the issue summary icon
- added a default order for the menu in the structure migration
- changed the default testing structure from *owner* to *userGroup*
- improved the tests

### Discussions
#### Changes
- upgraded the way that the *morphable* relation is handled similar to [AddressManager](#morphable) was changed
- improved validation
- dropped `TaggableUsers` structure (controller, response, route/permission) in favour of the core's `UserSelectController

#### Upgrade
The upgrade steps are similar to [AddressManager](#morphable)

### DocumentsManager
#### Changes
- upgraded the way that the *morphable* relation is handled similar to [AddressManager](#morphable) was changed
- improved validation

#### Upgrade
The upgrade steps are similar to [AddressManager](#morphable)

### Examples
- updated the provided import to work with `UserGroup`
- refactored the `ExampleFactory` and `SeniorityEnum`

### FileManager
- improved the tests
- added a default menu order

### FormBuilder
- enhanced the use of selects in templates. Now the `options` property can be provided with and `Enum`
- also strengthened the validations for selects
- added traits helpers for tests: `CreateForm`, `EditForm` & `DestroyForm`
- updates the switch css class form `is-success` to `is-info` 

### Helpers
#### Changes
- adds a `null` default return in `Enum@attributes()`
- renames the `IsActive` trait into `ActiveState`

#### Upgrade
Search in the whole project for `IsActive` and replace it with `ActiveState`, mind the casing.

### HistoryTracker
- improved test

### Impersonate
- improved tests

### Localisation
- added a publishable `LocalisationFactory`
- improved tests

### LogManager
- renamed `canBeSeen` to `visible` for the returned response
- added a reload control on the log card
- improved the tests

### MenuManager
- added missing `parent_id` in request validator
- improved `MenuFactory`
- improved tests

### Notifications
- cleaned up things in the controller
- renamed routes/permissions
- replaced the use of `vm.$bus` with `vm.$root`
- improved the tests

### People (*New*)
#### Features
- this package will handle people management in Enso
- full crud / menu
- multiple addresses
- a publishable `PeopleFactory`
- `Genders` and `Titles` enums.

#### Installation
It comes by default in an fresh Enso. To install it in an existing project follow the main upgrade steps provided above.

#### ToDo
Plans for the near future: 
- extend it to support customizations through publishable form and table templates
- customize validation rules through the config

### Select
#### Changes
- added a default limit of 100 to the backend builder
- renamed `optionsLimit` prop to `limit` for the `VueSelect` component and makes it optional
- renamed the default placeholder from `Please choose` to `Choose`
- added support for nested `$queryAttributes`. Check the `UserSelectController` for an example ;)

#### Upgrade
Replace in all instances where the component is used `optionsLimit` prop to `limit`. The prop being optional, the impact is minor even if the prop is not renamed

### StructureManager
#### Changes
- changed the default css class for edit & create pages to `<div class="column is-three-quarters-desktop is-full-touch">`
- renamed the `selectOptions` permission/route to `options`
- refactored test

#### Upgrade
In order to have a consistent look search in the app for `<div class="column is-three-quarters-desktop">` and replace it with `<div class="column is-three-quarters-desktop is-full-touch">`

### RoleManager
- fixes search in main table
- fixes `SupervisorId` constant

### Teams (*New*)
- initially part of the *core*, the structure was moved in its own package
- adds missing loading flag in `Index.vue`

#### Installation
It comes by default in an fresh Enso. To install it in an existing project:
 - `composer require laravel-enso/teams`
 - `php artisan migrate`
 - `php artisan vendor:publish --tag=teams-assets`
 - `yarn dev` / `npm run dev`

### TrackWho
- changed the traits to work only when the user is authenticated
- updated the `TrackWho` resource for the new core
- improved tests

### TutorialManager
#### Changes
- added a publishable `TutorialFactory`
- refactored the `Placement` Enum, positions start at 1 now.
- improved tests

#### Upgrade
Run this query in MySql: `update tutorials set placement = placement + 1`

#### VueComponents
- corrected typo in typeahead prop: `notResults` => `noResults`

### VueDatatable
- added support for nullable enum columns
- added a trait for tests: `DatatableTest`

## 2.12.0-beta

You definately shouldn't update to this version. In the next days we will refine the upgrade process and commit a new version with the full changelog. Stay tuned...

## 2.11.13
Improves history tabs. When updating existing apps use the delete button to clear previous tabs from the local storage to fully make use of the new behavior.

## 2.11.12

After a thorough analysis we decided to slighly change the behaviour of `TrackWho`. In all the traits `auth()->user()->id` was changed to `optional(auth()->user())->id`. This way we allow easier tests, seeders, tinker playing etc., on models that use one ore more of the three traits. In conjunction with this move, all the tables that have `created_by` should update the column to be `nullable()`. For Enso's tables we added a command for this `enso:track-who:update`
- removes unnecessary logic from `UserSeeder` and `OwnerSeeder`
- fixes tabs/custom tabs css

Upgrade steps:
- run `composer update`
- run `php artisan enso:track-who:update`
- update user and owner seeders with the ones from `laravel-enso/core` if they are not customized already (optional)

## 2.11.11
- fixes team filtering and updating bugs
- adds `scopes` to searchable models
- replaces `uniqid` with `Str::random(40)` for avatar generation
- throws and error in dataimport when importing blank sheets
- minor fixes in examples

## 2.11.10

- adds the new searchable package. Users have now the ability to search from a central place to any model from the application that is defined in the searchable config.

## 2.11.9

### Changes

#### Main project
- removes the users table migration from the main project - this migration is included in the core package
- updates composer and npm dependencies

#### Core
- improves the progress bar
- removes opacity from `.raises-on-hover`
- improves `GuestState`'s extensibility laravel-enso/core#69
- adds `touch` route to excluded list for history tabs
- updates user & owner seeders
- updates history to false in default preferences
- adds `created_at` in user & owner table
- adds morphable traits back to the owner until having a proper example structure for this packages

#### Contacts
- adds `created_at` in main table

#### FormBuilder
- adds `dateFormat` attribute in `forms.php` config file
- automatically converts `datepicker` field types to the default (config) format if no specific format is provided in the field's `meta` array

#### Impersonate
- fixes tests laravel-enso/impersonate#5

#### Localisation
- removes `updated_at` from the main tables.

#### MenuManager
- removes `updated_at` from the main tables.

#### PermissionsManager
- removes `updated_at` from permissions and permissions groups tables.

#### RolesManager
- removes `updated_at` from the main tables and adds the default menu instead.
- improves `RoleSeeder`

#### StructureManager
- fixes generation for multi word models

#### TestHelper
- removes max execution time laravel-enso/testhelper#2

#### VueDatatable
- adds `notExportable` option
- makes `ExportExcel` dispatchable

#### VueComponents
- fixes custom tabs colors
- updates info box to play nice with .raises-on-hover

### Updating existing projects
- `composer update`
- publish the above mentioned seeders if needed
- `yarn dev`

## 2.11.8

- refactors all controllers to extend `Illuminate\Routing\Controller`
- adds morphable models traits in `App\User` instead of `Core\User`;
- excludes auth routes from history tabs
- fixes teams edit/store form validation handling

## 2.11.7

- fixes `ProgressBar`'s position
- makes pages layout consitent. All tables have now `class="box is-paddingless raises-on-hover is-rounded"` and all forms class="box raises-on-hover animated fadeIn"
- tweaks navbar and aside shadow
- removes the ` Index` termination from index pages title (fe routes)
- fixes laravel-enso/structuremanager#10
- moves the following packages from core composer json to the project's composer json: "addressesmanager","commentsmanager","contacts","controlpanelapi","dataimport","discussions","documentsmanager","how-to"

Upgrade: apply [these](https://github.com/laravel-enso/Enso/commit/daa1d5b1473c800cbdde1fcbd91249765bd2acd4#diff-b5d0ee8c97c7abd7e3fa29b9a27d1780) changes to `composer.json`, followed by a `composer update`

## 2.11.6

### Improves History Tabs
- adds a clear control
- prevents `notFound` and `unauthorized` for being pushed to the history tabs
- integrates with vuex and saves / loads configuration to / from localStorage

## 2.11.5

- adds `showQuote` to core's config (`config/enso/config.php`), a boolean flag that allows showing or disabling the home screen
- fixes history tabs title
- minor refactors

## 2.11.4

- makes history tabs draggable
- replaces intro.js with driver.js.
- fixes some inconsistencies in dataimport -> templates
- to upgrade an exising project run
    - `yarn add driver.js vuedraggable`
    - `yarn remove intro.js`
    - `yarn dev`

## 2.11.3

- adds history tabs for the default layout. Users can control the history mode from the settings bar.
- fixes some small bugs
- small refactoring for the store
- adds `enso:update-global-preferences` command

To upgrade an existing app after `composer update` run `php artisan enso:update-global-preferences' and you're done.

## 2.11.2

- rename `/resources/js/core/structure/settings/` to `/resources/js/core/structure/menu/
- `php artisan localisation:merge` was renamed to `php artisan enso:localisation:merge`
Update the command signature in your `comopser.json` in `"post-update-cmd"` event.


### Changes and Upgrade steps:

- cleans `preferences.json`, now it contains only global settings

Moves the remaining demo files / classes / routes into the `examples` package:  
    -  `app/Classes/LocalState.php` (delete your local one if not used)  
    -  `app/Http/Controllers/ChartController.php` (delete your local one if not used)  
    -  `app/Http/Controllers/ChartController.php` (delete your local one if not used)  
    -  `app/Importers/*` (delete your local one if not used). If you stil want to use the example importer update in `/config/enso/imports.php` the template path to `'template' => 'vendor/laravel-enso/examples/src/app/Imports/Templates/owners.json',`  
    - `routes/api.php` is now empty  
    - dashboard Index.vue file  

Finally, moves into core:
    - the front-end route for dashboard
    - the dashboard structure migration (delete your local one)
    - an empty dasbhoard `Index.vue` file

- after updating run `art vendor:publish --tag=enso-preferences --force`;

## 2.11.1

This update adds the WIP touch layout. Run `php artisan enso:clear-preferences` after the update.

## 2.11.0

This version is a step forward in the direction of abstracting Enso from a clean Laravel project.

Changes and upgrade instructions:

- update in your `composer.json` the `core` requirement to `"laravel-enso/core": "3.1.*",`
- the examples were moved in a dedicated `examples` package. To upgrade, remove everyting related to examples:
    - remove the files (copy everything below in one take and paste it into the terminal)
        ```
        rm app/Example.php
        rm -R app/Http/Controllers/Examples
        rm database/factories/ExampleFactory.php
        rm database/migrations/2018_01_29_204255_create_examples_table.php
        rm -R resources/views/examples
        rm -R resources/assets/js/tableExample.js
        rm -R resources/assets/js/selectExample.js
        ```
    - remove the routes under the `Examples` namespace from the `routes/web.php` file:
        ```php
        Route::namespace('Examples')
            ->prefix('examples')->as('examples.')
            ->group(function () {
                Route::view('table', 'examples.table.index')->name('table');

                Route::prefix('table')->as('table.')
                    ->group(function () {
                        Route::get('init', 'TableController@init')->name('init');
                        Route::get('data', 'TableController@data')->name('data');
                        Route::get('exportExcel', 'TableController@exportExcel')->name('exportExcel');
                    });

                Route::view('select', 'examples.select.index')->name('select');

                Route::prefix('select')->as('select.')
                    ->group(function () {
                        Route::get('employee', 'EmployeeSelectController@options')->name('employee');
                    });
            });
        ```
    - remove from webpack the following lines:
        ```js
        .js('resources/js/tableExample.js', 'public/js')
        .js('resources/js/selectExample.js', 'public/js')
        ```
    - NOTE: if you still need examples in your project (the dashboard for instance) run `composer require laravel-enso/examples`, put back in `webpack.js` the two lines from the previous step and run `php artisan vendor:publish --force --tag="examples-assets"
- the `Owner` & `User` models were moved to `core`. To upgrade
    - remove `app/Owner.php`. If in the future you will need customization over this model you can create it locally but be sure to extend the one from core.
    - update `app/User.php` content to:
        ```php
        <?php

            namespace App;

            use LaravelEnso\Core\app\Models\User as EnsoUser;

            class User extends EnsoUser
            {
                //
            }
        ```
        If you have customizations, add them as well
- the form and tables (buiders, template, controllers) and the corresponding routes were moved to the `core` package. To upgrade, remove the following files/routes (if you customized some of the files then skip those and the corresponding routes if appliable):
    - `app/Forms/Builders/UserForm.php`
    - `app/Forms/Builders/OwnerForm.php`
    - `app/Forms/Templates/user.php`
    - `app/Forms/Templates/owner.php`
    - `app/Tables/Builders/OwnerTable.php`
    - `app/Tables/Builders/UserTable.php`
    - `app/Tables/Templates/owners.php`
    - `app/Tables/Templates/users.php`
    - `app/Http/Controllers/Administration/*`
    - `app/Http/Requests/ValidateOwnerRequest.php`
    - `app/Http/Requests/ValidateUserRequest.php`
    - `database/migrations/2017_01_01_108000_create_owners_table.php`
    - `database/migrations/2018_01_29_204255_create_examples_table.php`
    - from the `api.php` remove the lines related to user & owner:
        ```php
        Route::middleware(['auth', 'core'])
            ->group(function () {
                Route::namespace('Administration')
                    ->prefix('administration')->as('administration.')
                    ->group(function () {
                        Route::namespace('Owner')
                            ->prefix('owners')->as('owners.')
                            ->group(function () {
                                Route::get('initTable', 'OwnerTableController@init')
                                    ->name('initTable');
                                Route::get('getTableData', 'OwnerTableController@data')
                                    ->name('getTableData');
                                Route::get('exportExcel', 'OwnerTableController@excel')
                                    ->name('exportExcel');

                                Route::get('selectOptions', 'OwnerSelectController@options')
                                    ->name('selectOptions');
                            });

                        Route::resource('owners', 'Owner\OwnerController', ['except' => ['show', 'index']]);

                        Route::namespace('User')
                            ->prefix('users')->as('users.')
                            ->group(function () {
                                Route::get('initTable', 'UserTableController@init')
                                    ->name('initTable');
                                Route::get('getTableData', 'UserTableController@data')
                                    ->name('getTableData');
                                Route::get('exportExcel', 'UserTableController@excel')
                                    ->name('exportExcel');

                                Route::get('selectOptions', 'UserSelectController@options')
                                    ->name('selectOptions');
                            });

                        Route::resource('users', 'User\UserController', ['except' => ['index']]);
                    });
            });
        ```
- the user & owner tests were moved to the `core` package. to upgrade:
    - add in your `phpunit.xml` the `core` test suite
        ```xml
        <testsuite name="core">
            <directory suffix="Test.php">./vendor/laravel-enso/core/tests/features</directory>
        </testsuite>
        ```
    - remove `tests/Feature/OwnerTest.php`
    - remove `tests/Feature/UserTest.php`
- the `UserPolicy` was moved to the `core` package and consolidated with the `ImpersonatePolicy`. Also the `Horizon` authentication was moved to the `core` `AuthServiceProvider` upgrade:
    - remove `app/Policies/UserPolicy.php`
    - update `app/Providers/AuthServiceProvider.php` to to following content:
        ```php
        <?php

        namespace App\Providers;

        use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

        class AuthServiceProvider extends ServiceProvider
        {
            protected $policies = [
                //
            ];

            public function boot()
            {
                $this->registerPolicies();
            }
        }

        ```
- update the `Owner` namespace in the following files:
    - `app/Imports/Importers/OwnerImporter.php`
    - `config/enso/addresses.php` (if used)
    - `config/enso/contacts.php`  (if used)
    - `config/enso/comments.php`  (if used)
    - `config/enso/documents.php`  (if used)
    - `config/enso/discussions.php`  (if used)
    - `config/enso/config.php` (the `ownerModel`  key)
    - `database/factories/UserFactory.php`
    - `database/seeds/OwnerSeeder.php`
    - `database/seeds/UserSeeder.php`
- Note: If you used the `Owner` class for morphable packages, update the `morphable_type` in the `addresses`, `contacts`, `comments`, `documents` tables from `App\Owner` to `LaravelEnso\Core\app\Models\Owner`

## 2.10.8

- adds `laravel-enso/discussions`.

Steps for adding discussions in an existing project:

- `composer require laravel-enso/discussions`
- `php artisan vendor:publish --tag="discussions-config"` and then configure your desired model.
- `yarn add quill`
- `yarn add vue-quill-editor`
- compile
- add to `/resources/js/pages/administration/owners/Edit.vue` the `discussions` prop to `morphable-container`

## 2.10.7

### Changes

- moves `FileUploader.vue` & `Uploader.vue` from `laravel-enso/vuecomponents` to `laravel-enso/filemanager` 
- adds a new `MorphableContainer.vue` component. An use example can be seen in owner's edit page
- renames old `Addresses.vue` to `AddressesCard.vue`. Adds new `Addresses.vue` component to work with `MorphableContainer`
- renames old `Contacts.vue` to `ContactsCard.vue`. Adds new `Contacts.vue` component to work with `MorphableContainer`

### Upgrading existing projects:

- update `/resources/js/pages/administration/owners/Edit.vue` to match the file from the repo to use the new `MorphableContainer.vue`
- run `php artisan enso:documents:remove-deprecated-permissions`

## 2.10.6

- adds the new `Files` menu. The users can see their files in a centralised way.
- adds datatable and files integration. Your exports can now be downloaded from the app.
- for existing projects run:
- `composer require laravel-enso/dataexport`
- `composer update` 
- `php artisan migrate`
- `npm run dev`

## 2.10.5

### Changes & Fixes

#### Helpers
- adds a `JsonParser` helper class that is used now by dataimport, vuedatatable & formbuilder

#### Dataimport
- improves the template validations
- improves the uploaded file validation
- updates the config file - adds option to toggle validations depending the env (vuedatatable style)
- implements `JsonParser`

#### StructureManager
- fixes tests
- adds missing classes to structuremanager's table stub (index.vue)

### Core, VueDatatable, FormBuilder, Localisation
- implements `JsonParser`
#### General
- fixes issue in user form where the encrypted password was sent to front-end

### Upgrade an existing project
- update in `composer.json`: "laravel-enso/dataimport": "2.4.*",
- `composer update`
- `npm run dev`
- update the `config/enso/imports.php` to match the enhanced structure

## 2.10.4

- fixes structure manager to match the new resources folder
- fixes toastr css

## 2.10.3

- fixes user form template (`app/Forms/Templates/user.json`) `password` & `password_confirmation` fields hidden property - it needs to be a boolean. Update this manually in your local project.

- changes the unique-request-at-once-rule implemented in v2.10.1 to apply only on non `get` requests. I'm still waiting feedback for this ;)

- fixes some edge cases potential bugs in `vue-select` and `vue-form`

## 2.10.2

Laravel 5.7 adds, as usual, small changes to the core / config files. A new thing is the flat resources folder.

This update aligns everything in Enso with Laravel 5.7's changes.

Updating an existing project:

- move everything from `resources/assets/*` to `resources/*`
- manually update/add the following files to match the ones from Enso's repo:
    - `app/Http/Controllers/Auth/VerificationController.php`
    - `app/Http/Kernel.php`
    - `app/Http/Middleware/Authenticate.php`
    - `app/Http/Middleware/VerifyCsrfToken.php`
    - `app/Providers/RouteServiceProvider.php`
    - `config/app.php`
    - `config/enso/config.php`
    - `config/hashing.php`
    - `config/logging.php`
    - `config/services.php`
    - `config/session.php`
    - `tests/CreatesApplication.php`
    - `webpack.mix.js`

- update in `composer.json`
    - `"laravel-enso/charts": "2.5.*",`
    - `"laravel-enso/core": "2.15.*",`

## 2.10.1

Laravel 5.7 upgrade

We decided to cleanup the project's root and to try to make Enso installable on any existing or new Laravel application. This is a direction / objective that we'll follow in the near future.

### Changes

- Laravel 5.7 upgrade :)
- improved Enso's `Toastr`, now allowing 6 positions (desktop corners)
- new preferences setting that for customizing the toastr position
- bulma dropdown now hides after a selection is made by default. Has a new `hides-manually` prop
- updated the vuedatatable style selector and columns visibility dropdowns
- updated the dropdowns from setttings (right aside bar)
- moves the auth controllers to the core package, leaving the ones from the base project back to Laravel's original.
- removed the deprecated `Nprogress.vue` component from `vue-components` repo
- replaces `nprogress` dependency with an in house progress bar implementation that is integrated in the core. Now the animation is indeed controlled and the effect more natural
- adds a `requests` array to the app's state that tracks all the pending requests. From now on the application won't allow a second requests to the same endpoint / method before receiving the response for the initial one. Le me know your thoughs on this...
- adds the missing `StructureManager` tests to `phpunit.xml`
- adds desktop notifications - ref laravel-enso/enso#136

### Upgrading an existing project

- first be sure that you follow [L5.7 upgrade guide](https://laravel.com/docs/5.7/upgrade)
- update in your `composer.json`: `"laravel/framework": "5.7.*",`
- update in your `composer.json`: `"sentry/sentry-laravel": "dev-laravel-57@dev",`
- unless you already customized the auth routes already remove from your `api.php` routes file the whole route group under the `Auth` namespace. - now they are loaded from the core package
- replace the four auth controllers with the ones from this repo (`app/Http/Controllers/Auth/*`)
- add to your `phpunit.xml` the test suite for `StructureManager`
- run `php artisan enso:clear-preferences`
- run `php artisan vendor:publish --tag=enso-preferences --force`
- remove `nprogress` from your `package.json` and run `yarn upgrade`

## 2.10.0

This is a mostly a back end refactor.

Among the goals:
- dropping all the accessors (appends) that were using relationships in favor of getters
- adding query scopes where they were missing
- implementing resources ... something that we wanted for months
- making all FormBuilders DRY
- cleaning up and aligning all the service providers
- refined tests
- improving the form request validators
- getting the project mature...

### Additions & Changes

#### Activity Log
- refactored main query into scopes

#### AddressesManager
- adds `Address` resource
- adds `buildingTypes` and resource attributes to the config

#### AvatarMangager
- drops the `getAvatarIdAttribute`.
- adds an `created` event listener on the `User` model which automagically generates a new avatar for every created user
- adds the `generateAvatar()` method that can be called on any User. Note, in order to use this method you will have to be authenticated because the avatar relies on the `File` model that uses the `CreatedBy` trait.
- adds `php artisan enso:avatars:generate` - command that generates all the missing avatars

#### CommentsManager
- adds `Comment` resource. Major backend refactor

#### Core
- `Resource::withoutWrapping()` was added in the `AppServiceProvider`. Let's hope that this is a good decision :)
- `avatarId` was removed from the user's model `protected $fillable` array. Don't forget to do the same in your existing project(s). Read **Upgrade Instructions** for more details
- adds a `Team` resource
- adds the `Update` command

#### DocumentsManager:
- added document request validation
- added a new `ordered()` scope (most recent first)
- added the `Document` resource

### HowToVideo
- clean up, added resources

#### TrackWho
- we have now a `TrackWho` resource and a `trackWho` config (publishable). You can customize the attributes in the `TrackWho` resource from the config.

#### FileManager
- added the `File` resource.

#### FormBuilder
- fixed a validation for multiple selects with mutated value.

### RolesManager
- turned the `getRoleListAttribute` accessor into a getter - `rolesList()` in `HasRoles` trait. Updated the formbuilders for the models that were using this trait to use the getter instead of the accessor

### Upgrade Instructions *~ 3-5 mins*
- update in composer.json: "laravel-enso/core": "2.14.*"
- run `composer update`
- run `php artisan enso:update` - this command will align some inconsistent column types and clear unused columns
- remove the `avatarId` field from the `protected $fillable` array in your `App\User` model
- update the avatar column definition in your `app/Forms/Templates/users.json` table template to
```json
{
    "label": "",
    "name": "avatarId",
    "data": "avatars.avatarId",
    "meta": ["slot"],
    "class": "is-paddingless"
},
```
- remove from the same template (`users.json`) the deprecated `avatarId` from the appends array. The result should be `"appends": ["fullName"],`
- update in `app/Tables/Builders/UserTable.php` the query method to:
```php
public function query()
{
    return User::selectRaw(
            'users.id, users.id as "dtRowId", avatars.id as avatarId, owners.name as owner,
            users.first_name, users.last_name, users.phone, users.email, roles.name as role,
            users.is_active'
        )->join('owners', 'users.owner_id', '=', 'owners.id')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('avatars', 'users.id', '=', 'avatars.user_id');
}
```
- replace both `app/Forms/Builders/OwnerForm.php` & `app/Forms/Builders/UserForm.php` with the ones from the repo (this is not mandatory, and must be done with care if you have already customized your local builders)
```
- sync the `config/enso/config.php` keys with the keys from `vendor/laravel-enso/core/src/config/config.php`
- replace `tests/Feature/OwnerTest.php` &&  `tests/Feature/UserTest.php` with the one from the repo - or manually update if you have local changes
- modify the slot rendering in `resources/assets/js/pages/administration/users/Index.vue` to
```vue
<img class="is-rounded"
    :src="avatarLink(row.avatarId)"
    v-if="row.avatarId">
```
- add in 'database/seeds/UserSeeder.php` after the actual seed the following line`\Artisan::call('enso:avatars:generate');`

## 2.9.0

This version is a major backend refactor. It has breaking changes so read carefully the needed steps for upgrading existing projects. We created an artisan command that does all the hard work during the upgrade, so don't worry.

### Changes

#### File System
- the file system has been rewritten around a single `FileManager` class that makes full use of Laravel's `UploadedFile` class
- there is a `files` table now that keeps track of all the files in the application. The `File` model has a polymorphic 'attachable` relationship to any needed model.
- to develop new packages that work with file uploads all you need is the new `HasFile` trait and implementing the `Attachable` contract. 
The trait creates a `file` `morphOne` relationship to the base model and makes available all the needed methods: `inline()`, `download()`, `upload(UploadedFile $file)`. There are also helpers (check the contract / trait) and protected properties that can be used for configuring the specific of the model related to files, like:
    - `folder`: string the storage folder for the model's files
    - `mimeTypes`: array
    - `extensions`: array
    - `resizeImages`: array(width, height)
    - `optimizeImages`: boolean
- the trait adds a `deleting` event on the base model that makes sure the to associated files are delelted on `$model->delete()`
- when the env is testing `FileManager` uses a `testing` folder
- upgraded packages: AvatarManager, DataImport, DocumentsManager, FileManager, HowToVideos

#### Seeders
- until now the app was using migrations instead of seeders for inserting the default roles, owner and user. This version adds seeders for roles, default user, default owners, and countries (for the addresses package)
- the seeders can be published with `php artisan vendor:publish --tag=enso-seeders`. Use the `--force` flag if needed.

### Roles
- we have now a `php artisan enso:roles:sync` command that will help keeping the all the environments up to date.

### Upgrade steps for existing projects

- update in composer.json the following:
    - "laravel-enso/core": "2.13.*",
    - "laravel-enso/dataimport": "2.3.*",
    - "laravel-enso/documentsmanager": "2.3.*",
    - "laravel-enso/how-to": "2.1.*",
- run `composer update`
- run `php artisan migrate`
- run `php artisan enso:filemanager:upgrade`. This command will translate all the tables from the old format to the new one. It's recommended to make a backup of the database before the update, just to be sure that nothing goes wrong.
- run `php artisan vendor:publish --tag=enso-seeders --force` to update the seeders (Database, Role, Owner & User) if you didn't make any customizations to the existing ones.

#### Files & Folders to delete
- remove the `insert_default_owner` & `insert_default_user` migrations
- remove the `app/storage/temp` folder, it's no longer needed

#### Files that need to be replaced & edited according to the repo
- sync the `config/enso/config.php` keys with the keys from `vendor/laravel-enso/core/src/config/config.php`
- replace `tests/Feature/OwnerTest.php` with the one from the repo.
- replace `tests/Feature/UserTest.php` with the one from the repo.
- replace `resources/assets/js/pages/administration/users/Index.vue` with the one from the repo.
- edit `storage/app/.gitignore` file and replace `temp` with `testing`
- add in `app/User.php` the `avatarId` attribute to fillable
- add in `app/Tables/Builders/UserTable.php` `users.id` to the table select (`users.id, users.id as "dtRowId"`). We need this for showing the avatar in the users index table
- replace `app/Tables/Templates/users.json` with the one from the repo.

### Notes
- this is a pretty heavy upgrade, if you find any problems open issues in github, issues for this upgrade will be prioritized

## 2.8.29
- adds ability to delete individual notifications
- for existing projects run `php artisan enso:notifications:add-missing-permissions`

## 2.8.28
- fixes a series of small bugs in stub filling for StructureMigration
- adds "selectable" to the vue table - check the [example table](https://www.laravel-enso.com/examples/table)
- improves the form build when having an edit form. Now you can mutate field values for edit forms too with the `values()` method using the fluent syntax
- updates all composer & npm dependencies

## 2.8.27

### Notifications improvements

- adds a notifications index page that allows control over notifications from mobile devices too
- adds an icon attribute in the notification array
- to upgrade an existing project run `php artisan enso:notifications:add-missing-permission`

## 2.8.26

### Bug Fixes / Improvements for VueTable

- header label is now clickable for sortable columns
- overlay is moved to cover the whole component
- fixes slots for hidden columns in responsive mode
- auto refreshes the hidden columns in expanded rows on resize

## 2.8.25

### Bug Fixes
- VueSelect's search input padding
- ActivityLog's timeline background
- rotation of the burger on mobile devices
- missing args in store module `auth.js`

## 2.8.24

- fixes table export file name

## 2.8.23

- major redesign of vue-table component. The title / icon props were removed.

Upgrade steps to make your current project look like the demo.
    - update in `datatable.php`
        - **row** button classes to `is-row-button` (optional, old classes still work)
        - **global** button classes to null (optional)
        - `'style' => [ 'default' => ['hover', 'center'] ... ]`
    - update in all custom buttons in your templates, if it's the case
    - remove the `icon` and `name` props from all your table templates
    - replace all `vue-table` components class from `box` to `is-raised is-rounded`
    - add to all `vue-form` components `is-raised` class
    - compile all themes (uncomment everything from webpack.mix.js and run `yarn production` / `npm run prod`)

## 2.8.22

- improves user `show` page
- adds `.is-raised` helper
- adds link to author's profile in activity log

## 2.8.21

- adds ability to change the password from the user form. Users can change only their own password.

To upgrade an existing project you will have to copy by hand from a fresh Enso project the following files:

- `app/Forms/Builders/UserForm.php`
- `app/Forms/Templates/user.json`
- `app/Http/Controllers/Administration/User/UserController.php`
- `app/Http/Requests/ValidateUserRequest.php`
- `app/Policies/UserPolicy.php`
- `resources/assets/js/pages/administration/users/Edit.vue`

## 2.8.20

- adds the `Support Services` & `Sponsor` sections
- fixes a bug related to activitLog's FE route
- updates composer.json and packages.json dependencies

## 2.8.19

- improves the Teams menu
- adds the new Activity Log menu.

Now you can audit your user's activity within the application from the new menu.

To upgrade an existing project update in your `composer.json`: "laravel-enso/core": "2.12.*" and after the update run the migrations. If you want smarter logging for contacts, addresses, comments & documents update their configs matching the ones from a blank project.

The documentation will be updated soon.

Meanwhile, take a look at the new `LogsActivity` trait. For examples peek at the `User` model from a blank project and for polymorphic relations at the `Comment` model from the `CommentsManager` packages.

## 2.8.18

- adds custom totals in VueTable.

## 2.8.17

- upgrades Laravel to 5.6.31
- adds `app/Http/Middleware/CheckForMaintenanceMode.php` to the project. In an existing project you should copy it by hand if needed.
- improves the `users/Show.vue` controls
- improves the navbar, adds notifications indicator - WIP

## 2.8.16

- bugfixes
- dependencies update

## 2.8.15

- updates documentation
- updates composer and npm dependencies
- Breaking Change: updates fontawesome to 5.2.x. Please read FA [upgrade instructions](https://github.com/FortAwesome/Font-Awesome/blob/master/UPGRADING.md#50x-to-510)

Upgrade steps:
- update in `composer.json`:
    - core: `"laravel-enso/core": "2.11.*",`
    - charts: "laravel-enso/charts": "2.4.*",
- run composer update
- update in your packages.json file the fontawesome deps (replace the old with):
```json
"@fortawesome/fontawesome-free": "^5.2.0",
"@fortawesome/fontawesome-svg-core": "^1.2.2",
"@fortawesome/free-brands-svg-icons": "^5.2.0",
"@fortawesome/free-regular-svg-icons": "^5.2.0",
"@fortawesome/free-solid-svg-icons": "^5.2.0",
"@fortawesome/vue-fontawesome": "^0.1.1",
```

- replace in your whole project:
    - `import fontawesome from '@fortawesome/fontawesome'` with `import { library } from '@fortawesome/fontawesome-svg-core'`
    - `import FontAwesomeIcon from` with `import { FontAwesomeIcon } from`
    - `@fortawesome/fontawesome-free-regular/shakable.es` with `@fortawesome/free-regular-svg-icons`
    - `@fortawesome/fontawesome-free-solid/shakable.es` with `@fortawesome/free-solid-svg-icons`
    - `@fortawesome/fontawesome-free-brands/shakable.es` with `@fortawesome/free-brands-svg-icons`
    - `fontawesome.library.add` with `library.add`

## 2.8.14

- we have just launched the new documentation [website](https://docs.laravel-enso.com)
- on touch devices the menu hides once an option is selected
- refactor in menu drawing, the code is now DRY
- fixes bug in dataimport for rows that were ending in null cells
- cleans deprecated commands
- composer and node dependencies update

## 2.8.13

- upgrades the vue select to use Bulma's tag list in multiselect mode
- adds teams management. User can now be organized in teams. To enable the teams menu run `php artisan migrate`
- fixes the login redirect bug when session is expired and page is reloaded
- fixes inconsistency in vueSelect when the query became `null` instead of `''`, and unwanted requests were made
- hides the delete button for tags when the component is disabled

## 2.8.12

- updates json assertions in tests to work with Laravel's latest changes (laravel/framework#b41330319e954852d62bf955ca201ae371c29bce)
- You should manually update the `OwnerTest` & `UserTest` in existing projects.
- adds `InfoBox` and `InfoItem` in `vuecomponents`

## 2.8.11

- adds `laravolt/avatar` for creating default avatars. Avatars can be "refreshed" now
- vuedatatable: adds `dateFormat` template attribute for individual columns
- improves the overall flow for the structuremanager CLI
- fixes / improves tests (adds tests for structuremanager)
- fixes an edge case bug in vueselect multiple when clicking outside of the dropdown with no options selected

## 2.8.10

- adds animated hamburger for the main menu
- animates the notification dropdown arrow
- moves the Owner & User Tests in the local project & updates the phpunit.xml (**NOTE** you will have to do this yourself in an existing project)
- fixes laravel-enso/vuedatatable#67

## 2.8.9

- adds validations to structure manager's CLI
- updates the breadcrumbs for bulma 0.7.*
- improves the Enum, now it can handle constants
- fixes the comment manager dropdown (atwho)
- sets the card position to static (card.vue)
- fixes dataimport summary loading bug

## 2.8.8

### VueDatatable

NOTE: we removed the unused `writeSuffix` template attribute. Make sure that you remove it from all the existing templates.

### StructureManager

Finalizes the implementation for `php artisan enso:make:structure` - a CLI designed to help creating new ready for production Enso structures in minutes.

From the CLI you easily generate the following files:

- model
- `create_structure_for_model` migration
- front-end route files for crud actions `index`, `create`, `edit`, `show`
- crud views boilerplate
- form structure:
    - builder
    - json template boilerplate
    - request validator,
    - controller
- table structure:
    - builder
    - json template boilerplate
    - controller
- select structure
    - controller

and the needed backend routes (api.php)

### RoleManager

Adds the new system that allows roles management in development with the posibility to easily sync them with the live instance of the app though a seeder.

To run the syncronization, after generating each config file run on the live instance `php artisan db:seed --class=RoleSeeder.php

To use this run `php artisan enso:roles:add-missing-permission` after upgrading, and publish the [seeder]in your local project: `php artisan vendor-publish --tag=roles-seeder`

## 2.8.7

- compiles all the themes with latest bulma & bulma-extensions
- fixes navbar shadow

## 2.8.6

Pay attention, has one breaking change. See the upgrade section below.

### Contacts
- removes the obsolete `DropCreatedBy` command for contacts
- add the position field to the form & table
- moves the observations under a hoverable info icon
- changes the observations form field to a textarea

### Comments

- removes the obsolete `UpdateCommentsTable` command for comments
- raises the pagination default from 5 to 100

### Core
 
- removes the obsolete `DBRenameReserved` command

### DocumentsManager

- removes the obsolete `UpdateDocumentsPermissions` command
- adds authorization for link generation

### FormBuilder

- adds the `resize` boolean template attribute for textareas in formbuilder
- moves the validation error messages under the field

### RoleManager

- refactors the `HasRoles` trait - breaking change

### PermissionsManager

- removes the obsolete `AddMissingPermissions` command

### StructureManager

- finally adds a CLI for handling new structures. This is work in progress so what you see is what you get :)
To play with it run `php artisan enso:make:structure`.

### General

- updates composer and npm dependencies

### Upgrade an existing project

- Replace in your project's OwnerController file form
```
$owner->updateWithRoles(
    $request->all(),
    $request->get('roleList')
);
```

to

```
$owner->updateWithRoles($request->validated());
```

and do the same for `storeWithRoles`
## 2.8.5

- brings back the `webpack.mix.js` file
- fixes a bug in vuedatatable when saved start position is > total filtered

## 2.8.4

### Core
- refactors the FE menu initialisation
- fixes a bug when toggling menu visibility for touch devices

### Charts
- add support for vertical labels & removes skipping labels
- fully reactive when datasets / labels change, including in structure

### VueDatatable
- adds auth()->onceUsingId() for export jobs, useful for authorization
- fixes the `align` attribute for hidden columns in responsive mode

### General
- updates composer and npm dependencies

## 2.8.3

- adds `align` attribute for columns in vuedatatable templates
- removes the old alignment functionality from the FE
- removes the deprecated `customRender` function prop in favour of `slot` || `class`

## 2.8.2

### Improvements

- upgrades composer and npm dependencies

### Core
- adds jessenger/date as a requirement
- adds `showQuote` flag to `config/enso/config.php` which allows disabling the qoute from the home page
- after password reset the user is redirected to the login page

### Localisation
- integrates jessenger/date with the `SetLanguage` middleware.

### Vuedatatable
- adds a `request()` getter in table builders classes
- adds a `class` option for columns in template
- make the `loading` boolean flag available in scoped slots
- fixes a bug when exporting with rogue columns

### Formbuilder
- adds `Number` to vueswitch's `value` type

## 2.8.1
- fixes a routing problem for the login page.
- updates packages

## 2.8.0
This release is one of the biggest upgrades in a long time
- better organization for the FE files
- tons of bugs fixed on both the FE and the BE
- heavy refactoring
- flows optimisation

Changes overview:
- separated site layouts: `Auth`, `Home`, `Default`
- better management of transitions between different UI stages, such as authentication to main
- optimizations to the app routing 
- middleware is now used for the router; The users access will be filtered in the front-end too, based on their permissions; This means that all the FE permissions must be present in the `permissions` table and attached to the according roles. In the near future you'll be able to also add your own middleware too
- improved nprogress usage - applied through a router middleware 
- all layouts show the loading progress bar
- easier access to the nprogress object via the router, previous management via events has been removed as is no longer necessary
- refactored the authentication pages with the reuse of common elements
- the login, password reset and home pages are also themed
- resolved a situation where the login form would not be visible until refresh
- resolved visual issues during theme loading 
- improved the 'Page not found' page; added the 'Unauthorized' page
- improved title management for pages with a dedicated middleware, fixed issue where the title was missing in some scenarios and also translation issues
- addressed a role configurator bug when having more than one menu with the same name
- moved the logic for handling missing translation keys to the store `localisation` module
- many other front-end cleanup & optimizations 
- tree permission builder refactor for the back-end role configurator
- added logic to handle front-end only permissions, with no corresponding back-end routes

For an existing project the upgrade estimated time is ~ 5min. **TESTED**

To upgrade a project do the following:
* update `composer.json` for the new core version to `2.10.*` (please double check all the enso requirements against a composer.json from a fresh project)
* run `composer update`
* run `php artisan enso:add-missing-permissions`
* make sure to manually add to the permissions table all the FE-only permissions and attach them to the according roles
* following is the reorganized structure (read with care!!!) of the core application layout files:
    - `js/core` 
        - `structure`: the structural sub-components of the app
            - `navbar` (top)
            - `sidebar` (left) -> bring your own `/icons/app.js` file under this folder
            - `settings` (right sidebar)
            - `AppFooter.vue`: -> use your own file
            - `Breadcrumbs.vue`
            - `PageHeader.vue`
            - `VueAside.vue`
        - `App.vue`
        - `NotFound.vue`
        - `Router.vue` -> Update the path to this file in all the routes / pages that use the Router
        - `Unauthorized.vue`

* remove the old `js/pages/layout` with all its content

* nprogress usage
    - if using the nprogess events, replace old logic as these events have been removed:
        - `nprogress-add-request`
        - `nprogress-add-response`
        - `nprogress-done`
    - the nprogress instance is now directly available in the Vue root instance with `this.$router.app.$nprogress` with `incRequests()` / `incResponses()` methods

* middleware
    - the referenced files are:
        - `js/middleware/before/allow.js` -> used for restricting access on forbidden routes
        - `js/middleware/before/auth.js`
        - `js/middleware/before/nprogress.js`
        - `js/middleware/before/guest.js`
        - `js/middleware/after/nprogress.js`
        - `js/middleware/after/documentTitle.js`        
    
* authentication pages/components, will be published automatically on update
    - the referenced files are:
        - `js/pages/auth/AuthForm.vue`
        - `js/pages/auth/Login.vue`
        - `js/pages/auth/password/Email.vue`
        - `js/pages/auth/password/Reset.vue`
    
* dashboard
    - the referenced file is:
        - `js/pages/dashboard/Index.vue` -> it needs to be copied from the Enso repository, overwriting the existing file

* administration routes
    - the referenced files are:
        - `js/routes/administration.js`
        - `js/routes/administration/owners.js`
        - `js/routes/administration/users.js`
    - the above files have been updated and need to be copied from the Enso repository,  overwriting the existing files

* run `npm run dev`
* sync the routes in `routes/api.php` with the ones from a fresh project

## 2.7.12
- fixes the publishing path for `HowToVideo.vue`
- fixes keys in `Documents.vue`
- refactors CommentsManager with a breaking change.
To update an existing project first change the composer requirement to : "laravel-enso/commentsmanager": "2.3.*", and after the update run `php artisan enso:comments:update-table`

## 2.7.11
General BE refactor.

Steps for upgrade:
- update in your composer.json file the requirement for core: `"laravel-enso/core": "2.9.*"`
- `php artisan enso:update-action-logs-table`
- `composer update`
- `php artisan vendor:publish --tag=charts-config --force` for an updated color list.
- optionally list of files with small refactors which can be manually replaced from a fresh project: 'UserController.php', 'UserSelectController.php', 'ValidateOwnerRequest.php', 'ValidateUserRequest.php', 'User.php'

## 2.7.10
We finally ported the 'How To Videos' menu from to the SPA version of Enso.

To use it in an existing project do the following steps:
- `composer update`
- `composer require laravel-enso/how-to`
- `php artisan vendor:publish --tag=howToVideos-storage`
- `npm install --save vue-video-player`
- compile
- add in your `config/enso/config.php`, to the `paths` array the following entry: `'howToVideos' => 'howToVideos'`
- add in `storage/app/.gitignore` => `!howToVideos/`

## 2.7.9
- improves `laravel-enso/versioning` - read the updated documentation. It's a breaking change so be sure to update the code accordingly, and then in composer.json also update the require for `"laravel-enso/versioning": "1.1.*"`
- fixes a bug in auth.vue

## 2.7.8
- improves the mail templates
- adds to `config/enso/config.php` the following keys that are used for links in all the email notifications:
    - 'facebook' => ''
    - 'googleplus' => ''
    - 'twitter' => ''

To make use of the new templates in an existing project do the following steps:
    - publish the assets with `php artisan vendor:publish --tag=enso-mail-assets` (with `--force` if needed)
    - add the keys mentioned above to the `config/enso/config.php` file
    - update in `config/mail.php` the key `markdown.theme` from `default` to `enso`

## 2.7.7
- adds the ability to set a local state in the same request that builds Enso's state, by using the new config option `stateBuilder`. To use this feature point `stateBuilder` to a class that implements `LaravelEnso\Core\app\Contracts\StateBuilder` contract, and then make sure that you have the `resources/assets/js/localState.js` plugin, that should look like this:
    ```
    export default (context, state) => {
        // initalise the local state
    };
    ```
    where `state` is the what your local `StateBuilder` is returning.
- refactors `GuestController` by adding a `GuestState` Response
- adds the new laravel-enso/versioning package
- improves compatibility with PostgreSQL by renaming columns that are named with reserved words:
    - renames the "order" column to "order_index" in "menus" and "tutorials" tables
    - renames in "permissions" table the "default" column to "is_default"

Upgrade instructions:
- update `composer.json` to require `"laravel-enso/core": "2.8.*"`
- run `composer update`
- run `php artisan enso:db-rename-reserved` to update the database tables (`permissions`, `tutorials`, `menus`).
- update all your structure migrations (including Dashboard) / db seeders the columns accordingly `["order" => "order_index", "default" => "is_default"]`

## 2.7.6

- fixes missing login redirect
- removes the obsolete created_by column in laravel-enso/contacts

For existing projects first update your `composer.json` to require:`"laravel-enso/contacts": "2.2.*"` and after updating run `php artisan enso:contacts:drop-created-by` to drop the fk/column.

## 2.7.5
- removes the created_by column in addressesmanager
- upgrades the share link modal for documents
- updates all composer / npm  packages

For existing projects first update your `composer.json` to require:`"laravel-enso/addressesmanager": "2.3.*"` and after updating run `php artisan enso:addresses:drop-created-by` to drop the fk/column.

## 2.7.4
- adds temporary link generation to Documents. To use this feature do the following steps:
    - run `php artisan enso:update-documents-permissions`
    - and after attach the desired roles to the new `core.documents.link` permission
    - you also have to add to the `documents.php` config file the new `linkExpiration` option
    - make sure that you have the lastest `app/Http/Kernel.php` file, or add `'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,` to the `protected $routeMiddleware` array
- fixes localisation in auth views
- adds `disable-clear` prop to vue-select
- in structuremanager the created / deletion is wrapped now in transactions; adds generic exception when attribute validation in not passed
- removes the old and unused `config/enso/labels.php`. Make sure that you seach your whole project for `config('enso.labels` and clean everything up.

## 2.7.3
- the new structuremanager has now validations for all properties.
- all the tests are updated to use the new `ownerModel` option from the config.

Upgrade instructions:
- be sure that every `create_structure_migration` in your project that creates a menu has in the menu array the `order` key. You can default it to 999.

## 2.7.2
- refines the `Responsable` implementation
- adds `config('enso.config.ownerModel')` option to the config. By default it point to `App\Owner` class
- updates all the packages to use the new `ownerModel` option in config
- refactors the structuremanager package
- fixes a bug in tutorial.vue

Upgrade instructions:

- Copy from a fresh project the 'app\Http\Controllers\ChartController.php'. Copy the `resources/assets/js/pages/dashboard/Index.vue` component as well, if you didn't already for v2.7.1. Sorry about that, but what I did to the poor charts was an abomination and I had to roll it back.

## 2.7.1
- implements `Responsable` contract in the whole project
- refactors the BE

Upgrade instructions and detailed changes:

### Enso
    - renamed the `DashboardController` to `ChartController` and shortened all the routes / methods names for charts. Copy from a fresh project the 'app\Http\Controllers\ChartController.php' and remove the old `DashboardController.php`. Copy the `resources/assets/js/pages/dashboard/Index/vue` component as well.
    - update the `routes/api.php` to reflect the changes. Check `api.php` from a fresh project to see the exact changes.
    - update requirement in composer.json: "laravel-enso/core": "2.7.*",
    - update requirement in composer.json: "laravel-enso/charts": "2.3.*",

### Core
    - run `php artisan enso:clear-preferences'
    - publish the new preferences.json from the core package (with --force)
    
### Charts
    - Charts implements now the `Responsable` contract. You don't need to call `get()` anymore in your controller

### Comments
    - rename `editableTimeLimitInHours` to `editableTimeLimit` in `config/comments.php`

### Documents
    - rename `editableTimeLimitInHours` to `editableTimeLimit` in `config/documents.php`

### Forms
    - adds `route-params` prop that replaces the functionality of the old `params`
    - the old `params` prop is now and object and it's passed down to `vue-form`.
    - update the owner / user views (create / edit) accordingly, and the other files from your project where you are using `vue-form-ss`

## 2.7.0
We finally decided to start refactoring the store.

### Breaking Changes:
- refines user preferences. Now we have the ability to store local prefs using the view's route name. Docs will be updated soon.
- refactors the store modules
    - `locale.js` was renamed to `localisation.js` 
    - `preferences.js` is now responsible for everything related
    - `navbar.js` was renamed to `menu.js`

### Upgrade steps:
- run `php artisan enso:clear-preferences` to truncate the preferences table
- update composer.json requirement for "laravel-enso/core" : "2.6.*"
- run composer update

### Updates composer and npm packages.
- Bulma 0.7.1 ;)

## 2.6.13
- repairs the tests, closes laravel-enso/enso#97
- splits the factories into dedicated files

## 2.6.12
- adds taggable to vue-select - closes laravel-enso/select#14
- fixes laravel-enso/enso#80
- fixes laravel-enso/dataimport#59
- closes laravel-enso/vuedatatable#60

## 2.6.11
- adds mail templates concept - https://github.com/laravel-enso/Core/issues/56#issuecomment-387168277
- moves the users / owners migrations from core to enso
- closes laravel-enso/vuedatatable#59

### Updates composer and npm packages.

## 2.6.10

### Updates

- updates IntroJS use.

### Fixes

- fixes disabled state for vue-select
- fixes laravel-enso/enso#92
- fixes laravel-enso/formbuilder#35
- hides sections w/o visible fields in formbuilder
- adds reactive min/max to datepicker
- improves dataimport for some edge cases

### Updates composer and npm packages.

## 2.6.9

Improves the Enum class by adding an optional attributes method that can be used to set the default `$data` array.

Adds "actions" to `vue-table`. Now you can define a global button responsible for a custom action for all the filtered rows. Check the docs for more details

Improves the `vue-form` sections. Adds `show` helper. `show`, `hide`, `disable`, `readonly` can take now an array of fields too.

Fixes the modal's close button when using the card version.

Adds disabled state for `vue-select`

## 2.6.8

### Fixes
- the notification/toastr overflow-x.
- money bug where was not updated on `value` change from parent

### Enhancements:
- improves the `vue-form` and `vue-table` mobile layout by removing the text labels from global buttons.
- adds `is-reverse-moble` helper for columns.

### Updates composer and npm packages.

## 2.6.7

### Enhancements:
- Adds a "view" button to `vue-form`. Please read https://github.com/laravel-enso/FormBuilder/issues/30
- improves the file uploader scoped slot
- adds `disabled` state for tabs
- adds `is-danger` and `is-warning` boolean props for `datepicker`
- adds animations to comments & documents.
- sets vue-filter's `off-label` default to "".
- enhances and also fixes :), the `UserPolicy` and `UserController`. For existing projects you should copy the two files from this repo.
- implemented the new FE helper`canAccess` where there was the case
- removes `app.scss` from the core packages. This file is the entry point for the local project's scss customization.

### Fixes:
- scroll bug for the menu.
- dataimport index layout.
- various other small bugs.

### Updates composer and npm packages.

## 2.6.6
Fixes the permissions index table.

## 2.6.5
Updates vuedatatable to 1.1.x. This version separates the query from the Table controller in a dedicated ModelTable class. Now the Tables folder follows the structure from the Forms folder, with two subfolders, Builder and Templates. The readme will be updated soon, meanwhile you can check examples from the project.

For exising projects:
    - update in the composer.json "laravel-enso/vuedatatable": "1.1.*"
    - copy the UserTableController and OwnerTableController from this repo
    - copy the app/Tables folder from this repo
    - refactor all exising tables following the new style

## 2.6.4
Fixes bug in DocumentsCard / CommentsCard with loading flag.

Fixes the collapsed menu label.

Updates composer and npm packages.

## 2.6.3
Splits Documents.vue in two separate components. Now we have DocumentsCard as a wrapper and Documents.vue for custom implementations. The same for Comments.vue.

Upgrading existing projects:
    - Update in your composer.json: "laravel-enso/commentsmanager": "2.2.*" and "laravel-enso/documentsmanager": "2.2.*",
    - Update owners\Edit.vue from this repo, and any other views that use the updated components.

## 2.6.2
Improves notifications for vuedatable and comment tags.

Closes laravel-enso/vuedatatable#27

## 2.6.1
Fixes i18n import for date-fns.

## 2.6.0
Replaces `tightenco/ziggy` with an own implementation of the `route` helper. The new helper returns the directly the name route (string). Docs will be updated soon.

Improves the mechanism for switching themes.

Adds i18n support for date-fns. You can use the wrapper from `resources/assets/js/modules/enso/plugins/date-fns`.

Removes the global filter `timeFromNow`. Use instead the `formatDistance` wrapper from the above plugins dir.

Adds adds in the ajax errorHandler `loading = false` if the property exists.

The Card component now accepts both String and Object type for the icon prop.

The vue select autodetects if there is a global function `route` available. If the helper is present, the `source` prop is expected to be a named route and if not, an uri. (only when using the component with serverside)

Adds a dedicated modal for dataimport.

Existing projects:
    - Copy the `PermissionsUpdate` command from the latest release
    - Run `php artisan enso:permissions:update`
    - The new implementation for `route` should be compatible with ziggy's in most of the cases, but please test carefully after upgrade
    - don't forget to replace all occurencies of `timeFromNow`
    - update in `composer.json`: "laravel-enso/core": "2.5.*"

## 2.5.9
Adds a global helper for the FE called `canAccess(route)` than can be used to conditionally display sensible elements in the UI. The helper returns a boolean, and matches the allowed permissions for the user's role.

Allows full configuration over the theme list. You can add, remove or comment themes from `enso/themes.php`. From this version the config for themes is not merged anymore with the default one.

Updates the default datepicker theme to light, in the clean theme.
Improves the new `money` component.

Improves `vue-table` handling of `money` type.

Closes: 
    - laravel-enso/vuedatatable#46
    - laravel-enso/vuedatatable#47

Existing projects:
    - run `php artisan cleanup:permissions` to remove `core.home` (not used anymore).

And since v2.5.8, but missed in the changelog:
    - make sure that you get the new webpack.min.js file from this repo.
    - uncomment lines 12 - 32 and run `npm run prod` to compile and minimize all the themes, and after comment the lines back

## 2.5.8
Upgrades the system for switching themes.

Integrates the flatpickr themes with bulmaswatch, closes laravel-enso/formbuilder#25.

Refactors all old customRender functions to the new slot, in vue-table.

Updates composer / npm depedencies.

Changes the tag color for multiple select to info.

## 2.5.7
Adds money type for the formbuilder and vuedatatable packages. To upgrade you need to `npm install --save accounting-js`. The docs will be updated soon.

Changes the `__` from being defined extending the prototype to a mixin. This allows cleaner auto-detecting for the independend components.

Adds the `weekdays` prop to datepicker.

Closes:
    - laravel-enso/vuedatatable#1
    - laravel-enso/enso#86

## 2.5.6
Fixes laravel-enso/localisation#38

## 2.5.5

Adds localisation for the auth pages.

Adds localisation for the datepicker component.

Existing projects upgrade: - remove from `routes/api.php` the `getMeta` route (lines 3 to 5)

```
Route::get('/getMeta', function () {
    return ['appName' => config('app.name')];
})->name('getMeta');
```

Bug Fixes:
    - laravel-enso/formbuilder#24
    - laravel-enso/contacts#10
    - laravel-enso/permissionmanager#14
    - laravel-enso/localisation #35
    - laravel-enso/localisation #36

## 2.5.4
Adds "php artisan vendor:publish --tag='localisation-lang-files'" to composer.json "post-update-cmd". Please add this to existing projects by hand. This is needed to publish that laravel's native lang files when new languages are added.

Removes the auto merge when a new key is added.

Adds a Merge button for localisation that runs `php artisan localisation:merge`. This is available only in `env=local`.

Renames the localisation command from add-route to add-routes, as now it's responsibile for adding the merge route too.

Update composer / npm packages.

And finally adds an issue template for the github repo!

## 2.5.3
Adds enso json lang files to the assets that are published after every composer update. Executes the localisation:merge command after each composer update.

Updates major versions for npm / composer packages.

For existing projects: be sure that you copy the "post-update-cmd" section from laravel-enso/enso/composer.json to your composer.json.

## 2.5.2
Finally updates the charts lib. To upgrade run `npm install --save chartjs-plugin-datalabels file-saver`.

Add to composer.json requirements: "laravel-enso/charts": "2.2.*".

Copy from the demo project the updated `DashboardController`.

Improves the "select" package by adding the `trackBy` property in the BE. laravel-enso/select#13.

Fixes laravel-enso/localisation#28

## 2.5.1
Fix menu overflow-x.

## 2.5.0
Adds collapsible menu.

Adds switch in the settings bar for menu state. The state is saved in user's preferences.

Closes laravel-enso/localisation#15

For existing projects:
- update "composer.json" to require "laravel-enso/core" 2.4.*;
- run `php artisan vendor:publish --force` and choose the following tags:
    "core-preferences", "localisation-assets", "localisation-config", "localisation-lang-files"
- run `php artisan localisation:merge` to make sure that you have the newest lang files.
- run `php artisan localisation:add-route` for the new auto-collection-of-the-missing-keys ;)
- truncate the preferences table.

## 2.4.2
Changes the warning class to bulma's default.

Updates packages.

Fixes:
    - laravel-enso/impersonate#3
    -laravel-enso/core#46. Adds UserPolicy. For existing projects you should do the following steps:

Existing Projects:
- copy App\Policies\UserPolicy
- update App\Providers\AuthServiceProvider
- update App\Http\Controllers\Administration\UserController (update and destroy methods).

## 2.4.1
Fixes #76.

Adds filter for missing translation in Localisation/EditTexts

## 2.4.0
Refactors the enso.js file into dedicated modules.

Adds to the Vue prototype the `__` helper from localisation. Refactors the whole project to use the new helper - removes the mapGetters(['locale/__'])

Improves VueTable, VueForm and VueSelect to autodetect if the new `__` helper is available. This way we don't need to pass the helper every time.

Prepares the FE for the new auto-add-missing-key feature discussed [here](https://github.com/laravel-enso/Core/pull/45)

Moves the `debounce` prop for vuedatatable in the backend. Now `debounce` is a config option with a default of 100 (ms) that can be configured for everytable in its template.

To upgrade be sure that in your composer.json you have "laravel-enso/core": "2.3.*",

## 2.3.15
Brings back the ValidateUserRequest / ValidateOwnerRequest (deleted by mistake).

Updates composer and npm dependecies.

## 2.3.14
Greatly improves the datatables performance for big databases. The package can now handle tens of millions of records by using the new `fullInfoRecordLimit` config option. Read more in the docs.

Improves vuecomponents, vuetable and vueform.

Updates composer and npm dependecies.

## 2.3.13
Adds custom layout for form sections.

Fixes laravel-enso/select #11.

## 2.3.12
Breaking change: Upgrades the form builder to support sections. Beware, you will have to upgrade all the templates and wrapt the current `columns` / `fields` attributes in an `sections` array with at least one object (for only one section). Please look at the template.json example to understand the new structure. [another example](https://github.com/laravel-enso/FormBuilder/issues/9#issuecomment-373896117). Don't forget to also publish the new `forms.php` config file.

Fixes a bug when clearing the vuetable search field.

Updates all composer and npm dependencies.

## 2.3.11
Adds tutorials examples

## 2.3.10
Improves the vuetable to handle array values for its external filters

Upgrades the clear control for all input elements to bulma's default.

## 2.3.9
Small breaking change: The `$class` property from the vue select controllers is now changed to `$model`. You need to update this for `UserSelectController`.

Adds localisation and debounce props for the vueselect component.

Fixes:
    - laravel-enso/formbuilder#19
    - laravel-enso/vuedatatable#38

Updates all npm and composer packages

## 2.3.8
Improves the vuetable responsive calculation strategy.

Adds clear-button for typeahead.

## 2.3.7
Replaces the lib for detecting table resize.

Fixes lodash imports all over the project.

## 2.3.6
Adds hungarian language

Improves the the typeahead / laravel-enso/vuecomponents#12

Fixes the dataimport layout / laravel-enso/dataimport#13

Fixes the vuetable total when is crtNo is missing / laravel-enso/vuedatatable#32
\
Fixes the vuetable hidden controls when missing crtNo / laravel-enso/vuedatatable#36

Adds to the vuetable ability to have a scoped slot when using the `slot` meta attribute. The slot can be accessed using the name of the column. The props available are the `column` object and the `value` / laravel-enso/vuedatatable#34

Adds rogue columns to vuetable / laravel-enso/vuedatatable#33

Adds tooltips for vuetable header / laravel-enso/vuedatatable#31

Adds tooltips for vueform labels / laravel-enso/formbuilder#18

Improves the vuetable reponsiveness directive.

## 2.3.5
Closes laravel-enso/core#43, #44.

Fixes a modal and notification wrappers classes.

## 2.3.4
Improves the main modal component. Now the component uses a render function to make the modal a direct child of the body.

Upgrades the modal for vuedatatable and formbuilder.

## 2.3.3
Fixes laravel-enso/enso#66

Adds mongolian language

## 2.3.2
Fixes laravel-enso/enso#64

## 2.3.1
Fixes laravel-enso/vuedatatable#29.

Fixes laravel-enso/enso#63.

Fixes laravel-enso/enso#56

Puts all the auth routes to the except array for `VerifyCsrfToken` middleware

```
protected $except = ['api/login', 'api/password/email', 'api/password/reset'];
```

(you should do this step manually for existing projects)

## 2.3.0
Please follow the upgrade [instructions](https://github.com/laravel-enso/Enso/issues/62)

## 2.2.2
Packages updates. Bug fixes

## 2.2.1
Fixes bug in VueSelect & adds auto scroll when using keyboard navigation.

## 2.2.0
Removes vue-multiselect dependency. VueSelect has been rebuild from scratch and is now bulma themed. The option list builder has been upgraded too. Helpers/Enum has now a select() method that maps the $data to the expected format. VueSelect now uses an array of objects with the following format: [{id: 3, name: 'Label'}]. Select docs will be updated soon.

Upgrades the whole project for VueSelect, including the example page.

Fixes:
    - laravel-enso/vuedatatable#53
    - laravel-enso/enso#54
    - laravel-core/core#41

## 2.1.35
Adds `is_active` to languages table. A command will run "post-install" / "post-update".

Footer is now customizable larvel-enso/enso#52.

Adds Arabic Language.

## 2.1.34
Fixes the z-index problem for select / filters clear control.

## 2.1.33
Vue Routing refactor.

Vue files refactor to be compliant with eslint@4.3.0

Fixes #34, #38.

Renames init route `getAppName` => `getMeta`

## 2.1.32
Updates Laravel to 5.6.5.

Updates composer / npm packages.

Fixes the shadow for sidebars.

## 2.1.31
Adds Brazillian Portuguese localisation.

## 2.1.30
Updates all dependencies.

Improves vuedatatable's hidden columns.

Improves the typeahead component.
Fixes bug.

## 2.1.29
Upgrades date-fns to v2.0.0.alpha.7 to make full use of tree-shaking.

## 2.1.28
Adds back Sentry.

## 2.1.27
Fixes `vuedatatable` bug when building buttons for users w/o permissions.

Fixes user create form default value for the role select.

## 2.1.26
The app is now managed by Forge. Adds Horizon & Redis for jobs, cache.

## 2.1.25
Adds favicon notifications. Dont forget to npm install --save `favico.js`

Refactors `vuecomponents`. We don't want to enforce unnecessary classes where we can avoid it.

Adds to the `formbuilder` a new wrapper `<vue-form-ss>` that handles the ajax request for the form object. Adds a bunch of helpers methods for the builder. The documentatio will be updated soon, until then you can check any of the forms from the project. `laravel-enso/formbuilder#11'

## 2.1.24
Upgrades Laravel to 5.6 :). All the files from the local project are updated too.

Upgrades `laravel-mix` to 2.0.

Improves the `imagetransformer` package. Now uses for optimization `spatie/image-optimizer`. Adds `width` and `height` public methods.

Improves the use of `flags-icon-css`. The project now includes only the needed flags...

Improves the `fileuploader.vue` component - adds validation errors reporting (422).

Fixes bugs.

## 2.1.23
Fixes the `user_agent` column from the `logins` table, now its text. We use a command for this that executes at `post-cmd-install`/`post-cmd-update`.

Improves shadow for navbar / sidebar / settings bar.

Improves sticky footer, now uses flex instead of js.

Updates `dataimport` to the new version that uses `box/spout` instead of `Maatwebsite/Laravel-Excel`. There are breaking changes here, please read the `dataimport` docs.

Updates packages.

## 2.1.22
Extracted the `VueSelect.vue` and `VueSelectFilter.vue` to the Select package.
Packages update.

Note: the Vue components move means that you should update throughout your pages/components the import path to the 
select components and then delete the leftover components from the `vueforms` and `bulma` folders respectively: 
 
...`/vueforms/VueSelect.vue` to ...`/select/VueSelect.vue`

...`/bulma/VueSelectFilter.vue` to ...`/select/VueSelectFilter.vue`


## 2.1.21
Major update in DataImport. Improves interface, replaces Maatwebsite/Excel with Bos/Spout. The packages is now faster and cleaner.

Updates documentation for most of the packages.

Update composer and npm packages.

Note: Unfortunately we tagged wrongly "laravel-enso/menumanager" with 2.1.29 instead of 2.1.21. If you have the project already installed please correct this manually.

## 2.1.20
Fixes `core.index` route.

Packages update.

## 2.1.19
Adds Tabs/Tab vue components.

Makes Notifications sticky.

Improves example page.

Adds `optionsLimit` prop to VueSelect.

Fixes bugs.

## 2.1.18
Packages update.

Fixes VueSelect css.

## 2.1.17
Adds showcase for VueDatatable. The example Will be updated further.

## 2.1.16
Laravel Enso just got rid of jQuery, for good by replacing `atwho`, the latest dependency that was relying on jQuery, with a brand new custom component.

Fixes error reporting in auth views.

Adds zxcvbn for measuring password complexity.

## 2.1.15
Transitions are now fixed / more consistent.

The new toastr uses now Bulma's `notification` class instead of `message`.

## 2.1.14

Adds collapse animation for card.

Adds `extendedDocumentTitle` option to the `enso/config.php`. If this is set true it concats the app's name to the `document.title`. `document.title` is now translated.

Adds `icon` and `clickable` meta attributes for vudedatatable. `icon` is for rendering fa5 icons, while `clickable` can be used to customize behaviour when clicking specific cells from the table. The docs for vuedatatable will be extended soon to cover the new options.

Removes `favicon.ico` and `logo.svg` from the publishable resources. Now you can replace those with custom ones.

Packages update

## 2.1.13

Update to the core package AddressesManager to 2.2.0, which now uses the list of countries from 
[webpatser/laravel-countries](https://github.com/webpatser/laravel-countries).

Note that since the old list and the new list are different, this is a breaking change for the existing addresses (backup your data before upgrading). We include a command that will setup the new countries table for you post-install.

## 2.1.12
Packages update

## 2.1.11
Replaced `moment.js` with the great `date-fns`.

## 2.1.10
Imports missing icons.

Adds shadow to the toastr.

## 2.1.9
We're both happy and sad...

Happy because we have finally ditched the old `toastr` lib ($. dependent) and replaced it with our new toastr/message component.

Sad because this introduces a small breaking change. If you still want to use the old toastr you will have to include it yourself (hopefully not)., or you can just replace all the `toastr.${type}` occurencies with `this.$toastr.${type}` and go *nouveau* :).

This version also fixes the fa5 import - it now uses the `shakable.es` resource.

## 2.1.8
The overlay is now red, as Nprogress.

Package updates

## 2.1.7

### General
Registers globally the FontAwesomeIcon as 'fa'. It can be used anywhere in the project with `<fa icon="icon"></fa>`, as long as the needed icon is imported in that file.

Adds a new file where new menu icons should be registered `resources/assets/js/pages/layout/sidebar/icons/app`

Includes a command that will run automatically and update all the icons from the `menus` table to the new format.

Updates all the form & table json templates to work with fa5 icons.

### VueDatatable & FormBuilder
Changes the config file to work with the new style of including fa5 icons. Be sure to overwrite / update the old config file!

## 2.1.6
Upgrades the card vue component.

Minor layout fixes for vuedatatable and formbuilder.

## 2.1.5
Adds placeholder attribute for select field in formbuilder.

Fixes placeholder prop for VueSelect.

Refactors lolcalisation text edit vue.

Fixes routes in AddressesManager.

Package updates.

## 2.1.4
Updates npm dependencies / compiles.

Fixes Select List Option builder merging problem.

Fixes Addresses published form path.

## 2.1.3
Fixes VueDatatable nrcrt alignment.

## 2.1.2
Package updates

## 2.1.1
Fixes Role.php permissions and menus syncing.

Upgrades VueDatatable to the fa5 version.

## 2.1.0

### General
This version brings a major BE refactor for all the packages.

The separation between the M and the C in MVC is much clearer now.

Routes cleanup.

The vue components that were hosted in the generic `laravel-enso/vuecomponents` package and were belonging to specific packages where moved accordingly.

The BE tests are up to date.

Frontend refactor for all dropdowns.

Upgrades icons to Font Awesome 5 in the whole project / packages.

Note: If you have this project already, after pulling the current version and before installing the new packages two commands will run automatically to ensure route compatibility and font awesome icon upgrade for the legacy db table `menus` (composer.json -> "pre-install-cmd")

### Formbuilder
Slighly refactored and brings a dedicated `ModelForm` object.

Docs update comming soon.

### Select
All the properties were renamed.

### Filemanager
Improves the interface.

### VueDatatables
Dropdowns refactored.

### Vuecomponents
Introduces the `Dropdown` component for bulma.

### 2.0.35

Lots of visual improvements and layout fixes.

Adds profile menu link on touch devices.

### 2.0.34

Package updates

## 2.0.33

### Core
The custom code for all Enso Exceptions is now 555 instead of 455.

Removes unused `ProfilePageController` structure.

Renames `LogsSuccessfulLoginListener` to `LoginLoggerListener`

Fixes redirect to login page after logout

### DataImport
Refactors the config. Refactor error reporting and adds missing translations.

### FileManager
Updates the folder structure to follow Laravel format. Improves the interface of the `FileManager` class.

### Helpers
Updates the folder structure to follow Laravel format.

Moves `EnsoException` class to the Helpers package.

`EnsoException` is excepted from the Handler's report method and therefor is extended by all the project's custom exceptions.

### Select
Refactors the main class

### VueComponents
Fixes the file uploader when consecutive multiple uploads are needed

Fixes documents download

### General
Adds custom exceptions that extend `EnsoException` were the latter was previously used. Improves the error reporting  localisation.

Refactor and clean up code in migrations, Controllers / Services.


### Upgrade instructions:   
 - remove manually the `administration.users.updateProfile` route from the permissions menu
 - rename the dataimport config file from `importing.php` to `imports.php`. Remove `validationLabels` entry from `imports.php`. Place configs at the parent level - follow the format of the config from the package.
 - update the namespace for the classes provided by the helpers package: `Obj`, `Enum`, `IsActive` trait and `EnsoException`

## 2.0.32

Package updates
...

## 2.0.30

refactors the addresses routes

To upgrade do the following steps:
* update the migration batch number for `2017_12_07_150655_create_structure_for_addresses` to the latest batch + 1
* run `php artisan migrate:rollback`
* run `php artisan migrate`
* ensure that all the roles have the permissions set correctly for addresses
* manually remove the addresses.list permission

## 2.0.29
Various small fixes and cleanup for addresses

Upgraded the contacts component to use the vue-form. New routes and permissions 
were added and contacts structure migration has been changed (new edit and create permissions) 

To upgrade, the following steps are necessary:
* edit the migrations table record 2017_01_01_148000_create_structure_for_contacts, and set a high batch, say 999
* execute once `php artisan migrate:rollback` (so only THAT migration will be rolled back)
* execute `php artisan migrate`, so the new permissions will be inserted
* ensure that all the roles have the permissions set correctly for contacts

## 2.0.28

Upgrades the ios-switch to a new vue-switch component that is hosted in the formbuilder package

## 2.0.27
Addresses module is now included by default and is available in the owner edit page  

Packages update

## 2.0.26
Improved VueForm

Packages update

## 2.0.24

Fixes login error toastr

Packages update

...

## 2.0.20

### News

Finally decided to start keeping a changelog for the main project.

### Improvements

- pages, routes and store are not under `enso` subfolder anymore in order to maintain coherence when extending the project
- dynamic loading for store and routes via `storeImporter` and `routeImporter` helper functions
- lazy loading for routes
- removed the old prototypes and refactored the code accordingly
- visual refactor for `VueForm`, `VueSelect`, `Comments` and `Documents`. `Contacts` and `Addresses` will follow shortly
- improved the Formbuilder with full validation of the template
- new component: `Popup`
- major cleanup of all components, libs, imports
- jQuery is now used only in `Comments/Inputor.vue` until we find a js only lib to replace at.js
- under `assets/js`, for `pages`, `routes` and `store` the intermediate `enso` folder was removed, in order to maintain coherence when extending the project
- dynamic loading for store and routes via `storeImporter` and `routeImporter` helper functions
- lazy loading for routes
- removes the old `modules/enso/prototypes` and refactores the code accordingly
- visual refactor for `VueForm`, `VueSelect`, `Comments` and `Documents`. `Contacts` and `Addresses` will follow shortly
- improved the Formbuilder with full validation of the template
- new component: `Popup`
- major cleanup of all components, libs, imports
- jQuery is now used only in `Comments/Inputor.vue` until we find a js only lib to replace `at.js`

