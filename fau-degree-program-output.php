<?php

/**
 * Plugin Name: FAU Degree Program Output
 * Plugin URI:  https://github.com/RRZE-Webteam/FAU-Studium-Embed
 * Description: Fetch degree programs via the REST API and display them.
 * Version:     0.1.0
 * Author:      Inpsyde GmbH
 * Author URI:  https://inpsyde.com/
 * Update URI:  false
 * License:     GPL-2.0-or-later
 */

declare(strict_types=1);

namespace Fau\DegreeProgram\Output;

// phpcs:disable PSR1.Files.SideEffects

use Fau\DegreeProgram\Output\Infrastructure\Content\ContentModule;
use Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\RewriteModule;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Properties\PluginProperties;
use RuntimeException;
use Throwable;

/**
 * Display an error message in the WP admin.
 *
 * @param string $message The message content
 *
 * @return void
 */
function errorNotice(string $message): void
{
    add_action(
        'all_admin_notices',
        static function () use ($message) {
            $class = 'notice notice-error';
            printf(
                '<div class="%1$s"><p>%2$s</p></div>',
                esc_attr($class),
                wp_kses_post($message)
            );
        }
    );
}

/**
 * Handle any exception that might occur during plugin setup.
 *
 * @param Throwable $throwable The Exception
 *
 * @return void
 */
function handleException(Throwable $throwable): void
{
    do_action('inpsyde.fau-degree-program-output.critical', $throwable);

    errorNotice(
        sprintf(
            '<strong>Error:</strong> %s <br><pre>%s</pre>',
            $throwable->getMessage(),
            $throwable->getTraceAsString()
        )
    );
}

/**
 * Provide the plugin instance.
 *
 * @link https://github.com/inpsyde/modularity#access-from-external
 */
function plugin(): Package
{
    static $package;
    if (!$package) {
        $properties = PluginProperties::new(__FILE__);
        $package = Package::new($properties);
    }

    return $package;
}

/**
 * Initialize plugin.
 *
 * @throws Throwable
 */
function initialize(): void
{
    try {
        if (!is_readable(__DIR__ . '/vendor/autoload.php')) {
            throw new RuntimeException('Composer autoload file does not exist.');
        }

        require_once __DIR__ . '/vendor/autoload.php';

        // Initialize plugin
        plugin()->boot(
            new ContentModule(),
            new RewriteModule(),
            new RepositoryModule(),
        );
    } catch (Throwable $throwable) {
        handleException($throwable);
    }
}

add_action('plugins_loaded', __NAMESPACE__ . '\\initialize');
