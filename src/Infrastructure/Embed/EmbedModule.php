<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Repository\PostsRepository;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Psr\Container\ContainerInterface;

final class EmbedModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            EmbedTemplateFilter::class => static fn() => new EmbedTemplateFilter(),
            OembedProvidersFilter::class => static fn() => new OembedProvidersFilter(),
            EmbedHtmlFilter::class => static fn() => new EmbedHtmlFilter(),
            OembedRequestFilter::class => static fn(ContainerInterface $container) => new OembedRequestFilter(
                $container->get(PostsRepository::class),
            ),
            EmbedAssets::class => static fn(ContainerInterface $container) => new EmbedAssets(
                $container->get(Package::PROPERTIES),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_filter(
            'oembed_providers',
            [
                $container->get(OembedProvidersFilter::class),
                'addProvidingWebsite',
            ]
        );

        if (!$container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return true;
        }

        add_filter(
            'oembed_request_post_id',
            [$container->get(OembedRequestFilter::class), 'filterOembedRequest'],
            10,
            2
        );

        add_filter(
            'embed_html',
            [
                $container->get(EmbedHtmlFilter::class),
                'adjustIframeSize',
            ],
            10,
            4
        );

        self::addEmbedFilters(
            $container->get(EmbedTemplateFilter::class),
        );

        self::addPostDataFilters(
            $container->get(PostDataFilter::class)
        );

        add_action(
            'embed_head',
            [
                $container->get(EmbedAssets::class),
                'printStyles',
            ],
            9 // To remove default styles
        );

        add_action(
            'embed_footer',
            [
                $container->get(EmbedAssets::class),
                'printScripts',
            ]
        );

        return true;
    }

    private static function addEmbedFilters(EmbedTemplateFilter $embedTemplateFilter): void
    {
        add_filter(
            'embed_thumbnail_id',
            [
                $embedTemplateFilter,
                'removeThumbnail',
            ],
        );
        add_filter(
            'the_excerpt_embed',
            [
                $embedTemplateFilter,
                'removeExcerpt',
            ],
        );
        add_filter(
            'embed_site_title_html',
            [
                $embedTemplateFilter,
                'removeEmbedSiteTitle',
            ],
        );

        add_action('embed_content_meta', static function () {
            if (get_post_type() !== DegreeProgramPostType::KEY) {
                return;
            }

            remove_action('embed_content_meta', 'print_embed_comments_button');
            remove_action('embed_content_meta', 'print_embed_sharing_button');
            remove_action('embed_footer', 'print_embed_sharing_dialog');
        }, 0);

        add_action(
            'embed_content',
            [
                $embedTemplateFilter,
                'outputContent',
            ]
        );
    }

    private static function addPostDataFilters(PostDataFilter $postDataFilter): void
    {
        add_filter(
            'the_title',
            [
                $postDataFilter,
                'filterPostTitle',
            ],
            10,
            2
        );
        add_filter(
            'post_type_link',
            [
                $postDataFilter,
                'filterPostLink',
            ],
            10,
            2
        );
    }
}
