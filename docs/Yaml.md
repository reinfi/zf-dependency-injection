# Configuring YAML driver

In the `reinfi-di.local.php` file, you can uncomment the `extractor` and `extractor_options` keys to change the default extractor to Yaml as mapping source.

Make sure the `file` key under `extractor_options` points to a valid yml file with mapping information. Example configuration can look as follows:

```php
// config/autoload/reinfi-di.local.php file
// make sure config/services.yml is a valid yaml file

    'extractor'         => \Reinfi\DependencyInjection\Service\Extractor\YamlExtractor::class,
    'extractor_options' => ['file' => __DIR__ . '/../services.yml'],
```

## Example YAML file

```yml
# identifier of the service class
\App\Service\MyService:
    - { type: Inject, value: Laminas\EventManager\EventManager }
    - { type: Inject, value: application }

# Place here another mappings

```