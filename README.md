Configure dependency injection in Laminas or Mezzio using annotations, yaml or autowiring.

Heavily inspired by https://github.com/mikemix/mxdiModule.

=======

1. [Installation](#installation)
2. [AutoWiring](#autowiring)
3. [Attributes](#attributes)
4. [Annotations](#annotations)
5. [YAML](#yaml)
6. [Caching](#caching)
7. [PHPStan Extension](#phpstan-extension)
8. [Console commands](#console-commands)

### Installation

1. Install with Composer: `composer require reinfi/zf-dependency-injection`.
2. Enable the module via config in `application.config.php` under `modules` key:

```php
    return [
        'modules' => [
            'Reinfi\DependencyInjection',
            // other modules
        ],
    ];
```
### AutoWiring
To use autowiring for your service you need to specify the 'AutoWiringFactory' within the service manager configuration.
```php
'service_manager' => [
    'factories' => [
        YourService::class => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
    ],
]
```

#### Fallback AutoWiring
If you are migrating your existing project to use zend service manager and don't want to register all autowired services by hand, you can register the abstract `FallbackAutoWiringFactory` factory.

Please make sure that you don't use the fallback mechanism for everything. You should try to write and register explicit factories for your services.

```php
'service_manager' => [
    'abstract_factories' => [
        \Reinfi\DependencyInjection\AbstractFactory\FallbackAutoWiringFactory::class,
    ],
]
```

##### What can be autowired?
Every service registered within the service manager can be autowired.
Plugins within the plugin manager can also be autowired. If you need to register another mapping you can simply add the following:
```php
PluginManagerResolver::addMapping('MyInterfaceClass', 'MyPluginManager');
```
If your service needs the container as dependency this can also be autowired.
##### Add another resolver
If you like to add another resolver you can simply add one through the configuration.
```php
'reinfi.dependencyInjection' => [
    'autowire_resolver' => [
        AnotherResolver::class,
    ],
]
```
It needs to implement the ResolverInterface.

### Attributes
Attributes are activated if you are using a php version 8.0 or higher.
To use attributes for your dependencies you need to specify the 'InjectionFactory' within the service manager configuration.
```php
'service_manager' => [
    'factories' => [
        YourService::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
    ],
]
```
Following attributes are supported:
* Inject (directly injects a service from the service locator)
* InjectParent (must be used if you inject a service from a plugin manager)
* InjectConfig (dot separated path to a config value, e.g. service_manager.factories)
* InjectContainer (directly inject container interface)

Also in addition there a several annotations to inject from plugin managers.
* InjectViewHelper
* InjectFilter
* InjectInputFilter
* InjectValidator
* InjectHydrator
* InjectFormElement

You can either pass directly the required service name or if you need options you can pass them as following:
```php
#[InjectFormElement(name="Service", options={"field": "value"}]
```

If you need a doctrine repository there is also an attribute.
* InjectDoctrineRepository

It is only constructor injection supported, if you need di from setters you need to use delegator factories.

You can add the attributes at properties or at the __construct method.

```php
#[Inject("Namespace\MyService")] 
private MyService $service;

public function __construct(MyService $service) 
{
    $this->service = $service;
}
```
or
```php
private MyService $service;

#[Inject("Namespace\MyService")] 
public function __construct(MyService $service) 
{
    $this->service = $service;
}
```
The order is important and you should decide between constructor or property annotations.

### Annotations
To use annotations for your dependencies you need to specify the 'InjectionFactory' within the service manager configuration.
```php
'service_manager' => [
    'factories' => [
        YourService::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
    ],
]
```
Following annotations are supported:
* Inject (directly injects a service from the service locator)
* InjectParent (must be used if you inject a service from a plugin manager)
* InjectConfig (dot separated path to a config value, e.g. service_manager.factories)
* InjectContainer (directly inject container interface)

Also in addition there a several annotations to inject from plugin managers.
* InjectViewHelper
* InjectFilter
* InjectInputFilter
* InjectValidator
* InjectHydrator
* InjectFormElement

You can either pass directly the required service name or if you need options you can pass them as following:
```php
@InjectFormElement(name="Service", options={"field": "value"})
```

If you need a doctrine repository there is also an annotation.
* InjectDoctrineRepository

It is only constructor injection supported, if you need di from setters you need to use delegator factories.

You can add the annotations at properties or at the __construct method.

```php
use Reinfi\DependencyInjection\Annotation\Inject;

/**
 * @Inject("Namespace\MyService")
 */
private MyService $service;

/**
 * @param MyService $service
 */
public function __construct(
    MyService $service,
) {
    $this->service = $service;
}
```
or

```php
use Reinfi\DependencyInjection\Annotation\Inject;

/**
 * @Inject("Namespace\MyService")
 */
public function __construct(MyService $service) 
{
    $this->service = $service;
}
```
The order is important and you should decide between constructor or property annotations.
##### Adding own annotations
If you want to use your own annotation you just need to implement the AnnotationInterface.
### YAML
You can specify your dependencies within a yaml file.
```yaml
YourService:
  - {type: Inject, value: AnotherService}
```
To enable YAML usage you need to specify the following configuration
```php
'reinfi.dependencyInjection' => [
    'extractor' => YamlExtractor::class,
    'extractor_options => [
        'file' => 'path_to_your_yaml_file.yml',
    ],
]
```
### Caching
Parsing mapping sources is very heavy. You *should* enable the cache on production servers.
You can set up caching easily with any custom or pre-existing PSR-16 cache adapter.

You can provide a string which will be resolved by the container. The factory must return a PSR-16 cache adapter.
```php
'reinfi.dependencyInjection' => [
    'cache' => \Laminas\Cache\Storage\Adapter\Memory::class,
]
```
or you provide a factory for a cache adapter.
```php
'reinfi.dependencyInjection' => [
    'cache' => function() {
       return new \Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator(
           new \Laminas\Cache\Storage\Adapter\Memory()
       );
    },
]
```

### PHPStan Extension
As "autowiring" is always kind of magic this library ships with a PHPStan extension to solve that problem.

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
    <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include phpstan-extension.neon in your project's PHPStan config:

```
includes:
    - vendor/reinfi/zf-dependency-injection/phpstan-extension.neon
```

</details>

The extension requires to know your service manager to find all the classes you configured for autowiring.

If you do not provide it, the PHPStan extension will simply do nothing.

```neon
parameters:
    reinfiLaminasDependencyInjection:
       serviceManagerLoader: tests/service-manager.php
```

For example, `tests/service-manager.php` would look something like this:

```php
$app = \Laminas\Mvc\Application::init($config);
return $app->getServiceManager();
```


### Console commands
* Warmup script for Laminas: php bin/zf-dependency-injection-cache-warmup
  Fills the cache with every injection required by a class.
  This can either be via AutoWiringFactory or InjectionFactory.

### FAQ
Feel free to ask any questions or open own pull requests.
