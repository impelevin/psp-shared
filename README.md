## Installation

Run this command to install:
```bash
sail composer require impelevin/psp-shared
```
Add a provider to a Service Provider:
```php
IMPelevin\PSPShared\Providers\PSPSharedProvider::class
```

## How to use

### LTree

Extend your `Eloquent\Model` from `IMPelevin\PSPShared\LTree\Models\LTreeModel`.

Run this command to publish migration create a table used by LTree:
```bash
sail artisan vendor:publish --tag=psp-ltree-migrations
```

Use LTreeService for build path:
1. when create model: `lTreeCreatePath()`
2. when update model: `lTreeUpdatePath()` for update path for model and children
3. when delete model: `lTreeDropDescendants()` for delete children models

The `get()` method returns `LTreeCollection`, instead of the usual `Eloquent\Collection`.

`LTreeCollection` has a `toTree()` method that converts a flat collection to a tree.

`LTreeResourceCollection` & `LTreeResource`, which take `LTreeCollection` as an argument, will also be useful.

### Redirect to an external link from the Backend

Add to "App\Http\Kernel.php " middleware:

```php
\IMPelevin\PSPShared\Inertia\Middleware\RedirectExternalLocation::class
```
