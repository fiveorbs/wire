Changelog
=========

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Unreleased
----------

### Breaking Changes

- Add and use the `WireContainer` interface. 
- `CreatorInterface::create`'s parameter `$constructor` is now of type `string`
  instead of `string|null`. 


[0.3.0](https://github.com/fiveorbs/wire/releases/tag/0.3.0) - 2024-01-18
-------------------------------------------------------------------------

### Breaking Changes

- Changed the `Inject` attribute so that is is now annotated to parameters
  instead of functions or methods.

### Added

- Add `Type::Callback`.
- The optional `injectCallback` parameter to `Creator::create`.
- The optional `injectCallback` parameter to `CallableResolver::resolve`.
- The optional `injectCallback` parameter to `ConstructorResolver::resolve`.
- `Creator` now returns the container entry of the requested class if it
  exists. This way it supports instantiating interfaces if they are registered
  in the container.

[0.2.0](https://github.com/fiveorbs/wire/releases/tag/0.2.0) - 2024-01-05
-------------------------------------------------------------------------

Add predefined types.

### Added

- The `predefinedTypes` parameter to `Creator::create`.
- The `predefinedTypes` parameter to `CallableResolver::resolve`.
- The `predefinedTypes` parameter to `ConstructorResolver::resolve`.

[0.1.0](https://github.com/fiveorbs/wire/releases/tag/0.1.0) - 2023-11-11
-------------------------------------------------------------------------

Initial release.

### Added

- The `Wire` factory, which produces `Creator`, `CallableResolver` and `ContstructorResolver` instances.
- The `Inject` attribute.
- The `Call` attribute.
- The ability to be combined with PSR-11 containers.
