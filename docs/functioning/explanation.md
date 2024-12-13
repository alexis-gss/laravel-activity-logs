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

# 📑 Explanation

This package is quite simple to understand. It uses the dependencies of the Laravel framework to work well in a project using the same technology.

By adding the package trait to a specified model, we apply an observer to the model that listens to every action performed on the model :

<figure><img src="../.gitbook/assets/image (2).png" alt=""><figcaption></figcaption></figure>

When triggered, it will add a new entry to the `activity_logs` table with the type corresponding to the action :

<figure><img src="../.gitbook/assets/image (1).png" alt=""><figcaption><p>Laravel Activity Logs - action type</p></figcaption></figure>

In addition, the author of the action and the modified model fields will also be recorded in the table, so that you have a complete record of the modifications.

Now you know how [Laravel Activity Logs](https://packagist.org/packages/alexis-gss/laravel-activity-logs) package works.&#x20;
