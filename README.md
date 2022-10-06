# Buto-Plugin-ErrorPush
Push error data to a server using plugin error/log.

## Plugin wf/errorhandling (deprecated)
Checkout this plugin how to register a method.

## Event shutdown
````
events:
  shutdown:
    -
      plugin: 'error/push'
      method: 'shutdown'
      data:
        url: 'https://(domain)/error/insert'
````
