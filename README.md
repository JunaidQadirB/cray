# Cray For Laravel

A Laravel package to help you generate nearly complete CRUD pages like crazy!

## Install
`composer require junaidqadirb/cray`

## Configure
`php artisan vendor:publish --tag=cray`

## Use
`php artisan cray:scaffold Model`

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

