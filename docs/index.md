# Introduction

## What is Cray?

Cray is a `disposable` Laravel package to help you generate nearly complete CRUD pages like crazy. Literally. It's also
a bit opinionated.

If you build straight-forward CRUD pages more often manually writing all the same logic becomes a chore. Cray will not
only save you a save but also give you a better way to organize your code.

## So what do you mean by disposable?

You use Cray and forget about it. It isn't coupled with your installation of Laravel and it is preferred to be installed
as a `dev` dependency. Cray generates the files and it forgets about them, and they are all yours to modify or do
whatever you want.

## What does it actually do?

Suppose you are building a blog, and you want to create a Post model then you have to do a ton of other tedious and to
be honest, boring things like creating migrations, model factories, the controller, form validation and adding all the
logic and what not.

So what Cray does is when you tell it the model name, it will do all those boring things I listed earlier. When it's
done you have the following:

- `Post.php`
- `PostController.php` with all the necessary logic to list, edit, create and delete posts.
- `PostStoreRequest.php` and `PostUpdateRequest.php`
- Timestamped `create_posts_table.php` migration file
- `PostFactory.php`
- `posts` views directory
  with `index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, `modals/delete.balde.php`as well as a
  blank `_form.blade.php` for you to add the fields.
- `PostPolicy`
- Adds a `posts` resource route to the `routes/web.php` like this:
  ```php
  Route::resource('posts', App\Http\COntrollers\PostsController::class);
  ```

Then all you have to do is:

- Add the columns to the migration file
- Add the necessary fields (as defined in the last step) to the `_form.blade.php`
- Add validation rules for the fields you added to the form.
