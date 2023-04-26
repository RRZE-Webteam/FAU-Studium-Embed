<?php

/**
 * Plugin Name: FAU Degree Program Output
 * Plugin URI:  https://github.com/RRZE-Webteam/FAU-Studium-Embed
 * Description: Fetch degree programs via the REST API and display them.
 * Version:     0.1.0
 * Author:      Inpsyde GmbH
 * Author URI:  https://inpsyde.com/
 * Update URI:  false
 * GitHub Plugin URI: RRZE-Webteam/FAU-Studium-Embed
 * License:     GPL-2.0-or-later
 * Text Domain: fau-degree-program-output
 * Domain Path: /languages
 */

declare(strict_types=1);

namespace Fau\DegreeProgram\Output;

// phpcs:disable PSR1.Files.SideEffects

use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClientModule;
use Fau\DegreeProgram\Output\Infrastructure\Assets\AssetsModule;
use Fau\DegreeProgram\Output\Infrastructure\Cache\CacheModule;
use Fau\DegreeProgram\Output\Infrastructure\Cache\DailyCacheInvalidationRunner;
use Fau\DegreeProgram\Output\Infrastructure\CliModule;
use Fau\DegreeProgram\Output\Infrastructure\Component\ComponentModule;
use Fau\DegreeProgram\Output\Infrastructure\Content\ContentModule;
use Fau\DegreeProgram\Output\Infrastructure\Dashboard\AdminBarModule;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentModule;
use Fau\DegreeProgram\Output\Infrastructure\EventDispatcherModule;
use Fau\DegreeProgram\Output\Infrastructure\Filter\FilterModule;
use Fau\DegreeProgram\Output\Infrastructure\LoggerModule;
use Fau\DegreeProgram\Output\Infrastructure\Query\QueryModule;
use Fau\DegreeProgram\Output\Infrastructure\QueueModule;
use Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\RewriteModule;
use Fau\DegreeProgram\Output\Infrastructure\Search\SearchModule;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\ShortcodeModule;
use Fau\DegreeProgram\Output\Infrastructure\Template\TemplateModule;
use Fau\DegreeProgram\Output\Infrastructure\TranslationModule;
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
    do_action('fau.degree-program-output.critical', $throwable);

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

function setUpAutoloader(): void
{
    if (class_exists(ContentModule::class)) {
        return;
    }

    if (!is_readable(__DIR__ . '/vendor/autoload.php')) {
        throw new RuntimeException('Composer autoload file does not exist.');
    }

    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Initialize plugin.
 *
 * @throws Throwable
 */
function initialize(): void
{
    try {
        setUpAutoloader();

        // Initialize plugin
        plugin()->boot(
            new TranslationModule(),
            new ContentModule(),
            new RewriteModule(),
            new RepositoryModule(),
            new ApiClientModule(),
            new EnvironmentModule(),
            new LoggerModule(),
            new CacheModule(__FILE__),
            new AdminBarModule(),
            new CliModule(),
            new EventDispatcherModule(),
            new QueueModule(),
            new SearchModule(),
            new QueryModule(),
            new ComponentModule(),
            new ShortcodeModule(),
            new TemplateModule(),
            new AssetsModule(),
            new FilterModule(),
        );
    } catch (Throwable $throwable) {
        handleException($throwable);
    }
}

add_action('plugins_loaded', __NAMESPACE__ . '\\initialize');

register_activation_hook(
    __FILE__,
    static function (): void {
        setUpAutoloader();

        // Populate the cache on plugin activation
        wp_schedule_single_event(
            time(),
            DailyCacheInvalidationRunner::DAILY_CACHE_INVALIDATION_HOOK
        );
    },
);
