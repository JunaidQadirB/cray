# Cray For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/junaidqadirb/cray.svg?style=flat-square)](https://packagist.org/packages/junaidqadirb/cray)
[![Build Status](https://img.shields.io/travis/junaidqadirb/cray/master.svg?style=flat-square)](https://travis-ci.org/junaidqadirb/cray)
[![Quality Score](https://img.shields.io/scrutinizer/g/junaidqadirb/cray.svg?style=flat-square)](https://scrutinizer-ci.com/g/junaidqadirb/cray)
[![Total Downloads](https://img.shields.io/packagist/dt/junaidqadirb/cray.svg?style=flat-square)](https://packagist.org/packages/junaidqadirb/cray)



A Laravel package to help you generate nearly complete CRUD pages like crazy!

## Install
`composer require junaidqadirb/cray --dev`

## Configure
`php artisan vendor:publish --tag=cray`

## Use
`php artisan cray:scaffold Model`
or better
`php artisan cray Model`

The model you specify don't have to exisit.

If everything goes fine, you should get the following output:
```bash
Factory created successfully in [project path]/database/factories/ModelFactory.php
Created Migration: [timestamp]_create_models_table
Model created successfully in [project path]/app/Model.php
Controller created successfully in [project path]/app/Http/Controllers/ModelController.php
View successfully created in [project path]/resources/views/models/index.blade.php
View successfully created in [project path]/resources/views/models/_form.blade.php
View successfully created in [project path]/resources/views/models/modals/delete.blade.php
View successfully created in [project path]/resources/views/models/create.blade.php
View successfully created in [project path]/resources/views/models/edit.blade.php
View successfully created in [project path]/resources/views/models/show.blade.php
Request created successfully in [project path]/app/Http/Requests/ModelStoreRequest.php
Request created successfully in [project path]/app/Http/Requests/ModelUpdateRequest.php
```
### Specify Views Directory
`php artisan cray:scaffold Model --views-dir=dashboard`

### Specify Controller Namespace + Directory
`php artisan cray:scaffold Model --views-dir=dashboard --controller-dir=Dashboard`


### Changelog



## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email junaidqadirb@gmail.com instead of using the issue tracker.

## Credits

- [Junaid Qadir](https://github.com/junaidqadirb)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
