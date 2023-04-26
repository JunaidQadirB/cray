# The `cray` Command

The `cray` command is all you need when you want to scaffold artifacts for your model which includees:

- A model
- A controller, which contains all the CRUD actions with logic
- Two form requests, one each for update and store actions
- A migration
- A factory
- Policy
- views for each CRUD action including:
    - index
    - show
    - create
    - edit
    - delete
- A resource route

### Arguments

| Argument | Description              |
|:---------|:-------------------------|
| name     | Model name. **Required** |

The `cray` command can take a mix of the following optional options:

## The `base` Option

The base of all the files being generated. Project root is default if the base is not specified.

## The `views-dir` Option

This option allows you to place views in a subdirectory under the views directory or from the `base`, when specified.
It can be any nested directory structure from there.

## The `controller-dir` Option

This option places your controller in a subdirectory under the Http/Controllers directory. It can be any nested
directory structure.

## The `route-base` Option

This option allows you to define a base route name for the resources. Example: `dashboard.analytics`

## The `stubs-dir` Option

This option allows you to specify a custom `stubs` directory. By default, Cray comes with `stubs` which can be generated
using the following command:

```bash
php artisan vendor:publish --tag cray
```

> **Note:** Do not overwrite your Laravel stubs with Cray ones, or vice-versa because both have different placeholders.

## The `no-views` Option

When this option is passed, Cray will skip the creation of view files for the model.

## The `no-migration` Option

When this option is passed, Cray will skip the creation of migration for the model.

## The `no-factory` Option

When this option is passed, Cray will skip the creation of factory files for the model.

## The `namespace` Option

The namespace of all the files being generated. Project root is default 
