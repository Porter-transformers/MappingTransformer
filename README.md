
MappingTransformer
------------------

[![Latest version][Version image]][Releases]
[![Total downloads][Downloads image]][Downloads]
[![Build status][Build image]][Build]
[![Test coverage][Coverage image]][Coverage]
[![Code style][Style image]][Style]

MappingTransformer integrates [Mapper][Mapper] into [Porter][Porter] to support data transformations using `Mapping` objects. A full discussion of Mapper is beyond the scope of this document but the linked repository contains comprehensive documentation. MappingTransformer builds on Mapper by providing a powerful mapping strategy called `SubImport`.

### Sub-imports

The `SubImport` strategy provides a way to join data sets together. A mapping may contain any number of sub-imports, each of which may receive a different `ImportSpecification`. A sub-import causes Porter to begin a new import operation and thus supports all import options without limitation, including importing from different providers and applying a separate mapping to each sub-import.

#### Signature

```php
SubImport(ImportSpecification|callable $specificationOrCallback)
```

 1. `$specificationOrCallback` &ndash; Either an `ImportSpecification` instance or `callable` that returns such an instance.

#### ImportSpecification Example

The following example imports `MyImportSpecification` and copies the *foo* field from the input data into the output mapping. Next it performs a sub-import using `MyDetailsSpecification` and stores the result in the *details* key of the output mapping.

```php
$records = $porter->import(
    (new MyImportSpecification)
        ->setMapping(new AnonymousMapping([
            'foo' => new Copy('foo'),
            'details' => new SubImport(MyDetailsSpecification),
        ]))
);
```

#### Callback example

The following example is the same as the previous except `MyDetailsSpecification` now requires an identifier that is copied from *details_id* present in the input data. This is only possible using a callback since we cannot inject strategies inside specifications.

```php
$records = $porter->import(
    (new MyImportSpecification)
        ->setMapping(new AnonymousMapping([
            'foo' => new Copy('foo'),
            'details' => new SubImport(
                function (array $record) {
                    return new MyDetailsSpecification($record['details_id']);
                }
            ),
        ]))
);
```


  [Releases]: https://github.com/Porter-transformers/MappingTransformer/releases
  [Version image]: https://poser.pugx.org/transformers/mapping-transformer/version "Latest version"
  [Downloads]: https://packagist.org/packages/transformers/mapping-transformer
  [Downloads image]: https://poser.pugx.org/transformers/mapping-transformer/downloads "Total downloads"
  [Build]: https://github.com/Porter-transformers/MappingTransformer/actions/workflows/Tests.yaml
  [Build image]: https://github.com/Porter-transformers/MappingTransformer/actions/workflows/Tests.yaml/badge.svg "Build status"
  [Coverage]: https://coveralls.io/github/Porter-transformers/MappingTransformer
  [Coverage image]: https://coveralls.io/repos/github/Porter-transformers/MappingTransformer/badge.svg "Test coverage"
  [Style]: https://styleci.io/repos/76782166
  [Style image]: https://styleci.io/repos/76782166/shield?style=flat "Code style"

  [Porter]: https://github.com/ScriptFUSION/Porter
  [Mapper]: https://github.com/ScriptFUSION/Mapper
