<!-- markdownlint-disable MD024 -->
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Expand the admission requirements filter to include the "Admission free with restriction" option.
- Support filter reordering based on the `filters` attribute in the search shortcode.
- Support `ids` attribute in the search shortcode to restrict search results by Campo Keys.
- Support `german-language-skills-for-international-students` attribute in the search shortcode to restrict search results by language skills.
- Support `hide` attribute in the search shortcode to hide the heading (`heading`) or search form (`search`).

### Changed

- Dynamically load degree program search results when selecting checkboxes or typing a search term.
- Only the title, subtitle, and field "What is the degree program about?" are indexed for searching.
- Increase timeout limit when synchronizing data from the providing website.

### Fixed

- Degree program URL returns 404 for consuming websites.

## [1.0.2] - 2023-11-23

### Fixed

- Degree program combination URLs.

## [1.0.1] - 2023-08-16

### Fixed

- Allow switching the providing website.

## [1.0.0] - 2023-08-02

### Added

- Initial release.

[Unreleased]: https://github.com/RRZE-Webteam/FAU-Studium-Embed/compare/1.0.2...HEAD
[1.0.2]: https://github.com/RRZE-Webteam/FAU-Studium-Embed/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/RRZE-Webteam/FAU-Studium-Embed/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/RRZE-Webteam/FAU-Studium-Embed/releases/tag/1.0.0
