## Changelog
### v7.0.0
- annotations will be deprecated, use attributes instead
- YAML configuration will be deprecated, use attributes or autowiring instead
### v6.0.0
- minimum required PHP version 8.1
- added return types where possible
### v5.5.0
- extractor configuration can now contain an array of extractors 
### v5.0.0
- deprecated PHP 7.3
- add PHP 8.1
- add type hints
- increase PHPStan level 
### v4.3.0
- support PHP8
- removed dependency of laminas/laminas-cache
- cache requires now PSR-16 interface
- changed cache key configuration to be retrieved from container or callable
