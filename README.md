# Cray For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/junaidqadirb/cray.svg?style=flat-square)](https://packagist.org/packages/jq/cray)
[![Build Status](https://img.shields.io/travis/junaidqadirb/cray/master.svg?style=flat-square)](https://travis-ci.org/junaidqadirb/cray)
[![Quality Score](https://img.shields.io/scrutinizer/g/junaidqadirb/cray.svg?style=flat-square)](https://scrutinizer-ci.com/g/junaidqadirb/cray)
[![Total Downloads](https://img.shields.io/packagist/dt/junaidqadirb/cray.svg?style=flat-square)](https://packagist.org/packages/junaidqadirb/cray)


## What is Cray?

Cray is a `disposable` Laravel package to help you generate nearly complete CRUD pages like crazy. Literally. It's also a bit opinionated.



If you build straight-forward CRUD pages more often manually writing all the same logic becomes a chore. Cray will not only save you a save but also give you a better way to organize your code.



## So what do you mean by disposable?

You use Cray and forget about it. It isn't coupled with your installation of Laravel and it is preferred to be installed as a `dev` dependency. Cray generates the files and it forgets about them and they are all yours to modify do whatever you want.



## What does it actually do?

Suppose you are building a blog, and you want to create a Post model then you have to do a ton of other tedious and to be honest, boring things like creating migrations, model factories, the controller, form validation and adding all the logic and what not.



So what Cray does is when you tell it the model name, it will do all those boring things I listed earlier. When it's done you have the following:

- `Post.php`
- `PostController.php` with all the necessary logic to list, edit, create and delete posts.
- `PostStoreRequest.php` and `PostUpdateRequest.php` 
- Timestamped `create_posts_table.php` migration file
- `PostFactory.php`
- `posts` views directory with `index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, `modals/delete.balde.php`as well as a blank `_form.blade.php` for you to add the fields.



 Then all you have to do is: 

- Add the columns to the migration file
- Add the necessary fields (as defined in the last step) to the `_form.blade.php`
- Add validation rules for the fields you added to the form.



## Installation

```bash
composer require jq/cray --dev
```

Then publish the stubs

```bash
php artisan vendor:publish cray
```

It will generate `stubs` to `resources/vendor/cray/stubs` directory.



## Usage

```bash
php artisan cray Post
```

Once done, it will show you the details of the files generated.

```bash
Factory created successfully in /database/factories/PostFactory.php
Created Migration: 2020_03_14_151409_create_posts_table
Model created successfully in /app/Post.php
Controller created successfully in /app/Http/Controllers/PostController.php
View created successfully in /resources/views/posts/index.blade.php
View created successfully in /resources/views/posts/create.blade.php
View created successfully in /resources/views/posts/_form.blade.php
View created successfully in /resources/views/posts/edit.blade.php
View created successfully in /resources/views/posts/show.blade.php
View created successfully in /resources/views/posts/modals/delete.blade.php
Request created successfully in /app/Http/Requests/PostStoreRequest.php
Request created successfully in /app/Http/Requests/PostUpdateRequest.php
```

Now add the necessary fields and run

```bash
php artisan migrate
```

And that saved you an hour worth of repetitive and boring work which you can spend on more important development challenges.

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
