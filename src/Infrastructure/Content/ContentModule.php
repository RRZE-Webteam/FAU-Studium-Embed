<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Content;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\Taxonomy;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class ContentModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            TaxonomiesList::class => static fn() => TaxonomiesList::new(),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return false;
        }

        add_action('init', static function () use ($container): void {
            self::registerPostType();
            self::registerTaxonomies($container->get(TaxonomiesList::class));
        });

        return true;
    }

    private static function registerPostType(): void
    {
        register_post_type(
            DegreeProgramPostType::KEY,
            DegreeProgramPostType::hidden()->args()
        );
    }

    private static function registerTaxonomies(TaxonomiesList $taxonomiesList): void
    {
        foreach ($taxonomiesList as $taxonomyClass) {
            /** @var Taxonomy $taxonomyObject */
            // phpcs:ignore NeutronStandard.Functions.DisallowCallUserFunc.CallUserFunc
            $taxonomyObject = call_user_func([$taxonomyClass, 'hidden']);

            register_taxonomy(
                $taxonomyObject->key(),
                DegreeProgramPostType::KEY,
                $taxonomyObject->args()
            );
        }
    }
}
