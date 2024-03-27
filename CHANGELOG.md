# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Removed
- Removed PHP 8.0 from Q&A Github actions workflow
### Added|Changed|Deprecated|Removed|Fixed|Security
- Nothing so far

## 5.0.3 - 2023-09-08
### Fixed
- Fixed type errors in SvgService

## 5.0.2 - 2023-04-19
### Added
- Font-smoothing to styleguide components

## 5.0.1 - 2023-03-30
### Added
- Linter friendsofphp/php-cs-fixer
### Removed
- Linter zicht/standards-php

## 5.0.0 - 2022-10-06
### Added
- Support for Symfony ^5.4
### Removed
- Support for Symfony 4

## 4.0.1 - 2022-10-06
### Fixed
- Changed usages of deprecated `%kernel.root_dir%` parameter into `%kernel.project_dir%`

## 4.0.0 - 2022-04-29
### Removed
- Removed support for Symfony 3.4
- Removed support for PHP 7.2 and 7.3
- Rakit/validation package dependency and the `validate_context` Twig filter and the whole ValidateExtension
### Changed
- Replaced use of `Symfony\Bundle\FrameworkBundle\Templating\EngineInterface` with `Twig\Environment`
- Replaces SVG Service deprecated Simple Cache with Cache Adapters
- Update copyright comment in `_base.html.twig`: Changed Zicht to Fabrique
## Fixed
- Fixed several other deprecations
## Added
- Added Vimeo Psalm (level 4) and fixed all errors
- Added this CHANGELOG.md

## <4.0.0
### Added|Changed|Deprecated|Removed|Fixed|Security
- No changelog has been kept in versions earlier than 4.0.0
