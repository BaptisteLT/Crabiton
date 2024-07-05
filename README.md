-Pour compiler les assets (js et scss):
php bin/console sass:build --watch

-Pour consumer les mails asynchrones:
php bin/console messenger:consume -vv