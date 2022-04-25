
# Getting Started

## Installation

```bash
composer require jq/cray --dev
```
Or specify specific version, for example to try a beta

```bash
composer require "jq/cray:3.2.0-beta5" --dev
```

Then publish the stubs

```bash
php artisan vendor:publish --tag=cray
```

It will generate `stubs` to `resources/stubs` directory.


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

### Optional Packages
- [Blade Components](https://github.com/JunaidQadirB/blade-components)
