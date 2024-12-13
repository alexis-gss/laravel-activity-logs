---
layout:
  title:
    visible: true
  description:
    visible: false
  tableOfContents:
    visible: true
  outline:
    visible: true
  pagination:
    visible: true
---

# 📥 Installation

{% hint style="info" %}
[Laravel Activity Logs](https://packagist.org/packages/alexis-gss/laravel-activity-logs) requires [PHP 8.3+](https://www.php.net/releases/).
{% endhint %}

Add this composer package to your Laravel project:

```
composer req alexis-gss/laravel-activity-logs
```

Then run the migration to add a table of logs in your database :

```
php artisan migrate
```

Finally, for each model whose history you want to track, add the trait of the package :

```
use Illuminate\Database\Eloquent\Model;
use LaravelActivityLogs\Traits\ActivityLog;
...
class ModelName extends Model
{
    use ActivityLog;
    ...
}
```

That's it, now on each actions of the model, like update, you will be able to find a new line in the table `activity_logs`. It's up to you to create a specific page in your project to visualize all entries.
