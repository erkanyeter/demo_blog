## Task Helper

The task helper helps to you use CLI operations ( running shell scripts etc..) inside the php interface.

#### Running Tasks

The first segment calls the <kbd>module</kbd> and others call <kbd>controller/method/arguments</kbd>

```php
Task::run('welcome start/index/arg1/arg2');
```

Look at the Tasks and CLI Requests at <kbd>(/docs/advanced/tasks-and-cli-requests)</kbd> section for more details.

### Function Reference

------

#### Task::run('uri', $debug = false);

Using Task::run() function you can run your tasks as a command in the background.