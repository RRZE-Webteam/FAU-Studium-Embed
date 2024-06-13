# FAU Degree Program Output

Fetch degree programs via the REST API and display them.

## Table Of Contents

* [Installation](#installation)
* [Requirements](#requirements)
* [Usage](#usage)
* [Crafted by Syde](#crafted-by-syde)
* [License](#license)
* [Contributing](#contributing)

## Installation

The best way to use this package is to download the archive from the releases page on GitHub and
install it as a WordPress plugin.

## Requirements

The WordPress Cron API is required for the correct functioning of the plugin because it is used to
update the degree program cache. If the cron API is inactive, the cache will not be warmed after
invalidation, leading to performance issues.
If you can't use the WordPress Cron API, make sure you run cron jobs using the WP-CLI or a real Unix
cron job.

After plugin activation, a daily WordPress Cron job is registered to schedule cache invalidation and warming.
The behavior can be disabled by defining the PHP constant or environment variable `FAU_DISABLE_DAILY_CACHE_INVALIDATION`.
In this case, a Unix cron job that executes the WP-CLI command `wp fau cache invalidate` should be run regularly.
This is the preferred way for cache invalidation and warming
because there are no time and memory limits compared to WordPress Cron which runs within HTTP requests.

The plugin's logger uses [`error_log()`](https://www.php.net/manual/en/function.error-log.php) internally.

### Sorting functionality

The plugin allows users to order `WP_Query` post loops by selected post meta keys in both the frontend and backend.
In the frontend, visitors can use this feature to order the filter views by degree, study location, or the start of the degree program.
On the degree program management site, editors can use this feature to sort the backend post lists by degrees.

## Usage

The plugin fetches degree program data from <https://meinstudium.fau.de>. To change the domain
(for testing purposes only!), add an environment variable or PHP constant `FAU_DEGREE_PROGRAM_API_HOST`.

On the providing website, the "FAU Degree Program" plugin is installed for managing the degree programs.

The plugin provides the shortcode `[fau-studium]` with two main variations:

1. `[fau-studium display="search"]` to display
the degree programs overview ([documentation](./docs/degree_programs_search_shortcode.md))
2. `[fau-studium display="degree-program" id="123"]` to display
a single degree program ([documentation](./docs/single_degree_program_shortcode.md))


## Crafted by Syde

The team at [Syde](https://syde.com/) is engineering the Web since 2006.

## License

Copyright (c) 2022, Syde GmbH

This software is released under the ["GNU General Public License v2.0 or later"](LICENSE) license.

## Contributing

All feedback / bug reports / pull requests are welcome.
