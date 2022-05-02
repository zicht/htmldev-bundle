# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added|Changed|Deprecated|Removed|Fixed|Security
- Nothing so far

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
- Added this CHANGELOG.md

## <4.0.0
### Added|Changed|Deprecated|Removed|Fixed|Security
- No changelog has been kept in versions earlier than 4.0.0
