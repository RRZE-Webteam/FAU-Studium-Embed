<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\ConditionalFieldsFilter;
use Fau\DegreeProgram\Common\Application\Repository\CachedDegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\Repository\CampoKeysRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\FacultyRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use Fau\DegreeProgram\Common\Infrastructure\Repository\WordPressDatabaseDegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\WordPressDatabaseDegreeProgramRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\WordPressDatabaseDegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\WpQueryArgsBuilder;
use Fau\DegreeProgram\Common\Infrastructure\Sanitizer\HtmlDegreeProgramSanitizer;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Inpsyde\Modularity\Module\FactoryModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;

class RepositoryModule implements ServiceModule, FactoryModule
{
    use ModuleClassNameIdTrait;

    public const VIEW_REPOSITORY_UNCACHED = 'view_repository_uncached';
    public const COLLECTION_REPOSITORY_UNCACHED = 'collection_repository_uncached';

    public function services(): array
    {
        return
            $this->makeDatabaseViewRepositoryDefinition()
            + [
                PostsRepository::class => static fn() => new PostsRepository(),
                CampoKeysRepository::class => static fn() => new CampoKeysRepository(),
                WordPressApiDegreeProgramViewRepository::class => static fn(ContainerInterface $container) => new WordPressApiDegreeProgramViewRepository(
                    $container->get(ApiClient::class)
                ),
                DegreeProgramViewRepository::class => static fn(ContainerInterface $container) => new CachedDegreeProgramViewRepository(
                    $container->get(self::VIEW_REPOSITORY_UNCACHED),
                    $container->get(CacheKeyGenerator::class),
                    $container->get(CacheInterface::class),
                ),
                WpQueryArgsBuilder::class => static fn(ContainerInterface $container) => new WpQueryArgsBuilder(
                    $container->get(TaxonomiesList::class),
                    $container->get(CampoKeysRepository::class),
                ),
                WordPressDatabaseDegreeProgramCollectionRepository::class => static fn(ContainerInterface $container) => new WordPressDatabaseDegreeProgramCollectionRepository(
                    $container->get(DegreeProgramViewRepository::class),
                    $container->get(WpQueryArgsBuilder::class),
                ),
                DegreeProgramCollectionRepository::class => static fn(ContainerInterface $container) => new CachedApiCollectionRepository(
                    $container->get(WordPressDatabaseDegreeProgramCollectionRepository::class),
                ),
                IdGenerator::class => static fn() => new IdGenerator(),
                OriginalDegreeProgramViewRepository::class => static fn(ContainerInterface $container) => new WordPressDatabaseOriginalDegreeProgramViewRepository(
                    $container->get(EnvironmentDetector::class),
                ),
                WordPressTermRepository::class => static fn() => new WordPressTermRepository(),
                CurrentViewRepository::class => static fn(ContainerInterface $container) => new CurrentViewRepository(
                    $container->get(DegreeProgramViewRepository::class),
                    $container->get(CurrentRequest::class),
                ),
            ];
    }

    public function factories(): array
    {
        return [
            self::VIEW_REPOSITORY_UNCACHED => static function (ContainerInterface $container): DegreeProgramViewRepository {
                return $container->get(EnvironmentDetector::class)->isProvidingWebsite()
                    ? $container->get(WordPressDatabaseDegreeProgramViewRepository::class)
                    : $container->get(WordPressApiDegreeProgramViewRepository::class);
            },
            self::COLLECTION_REPOSITORY_UNCACHED => static function (ContainerInterface $container): DegreeProgramCollectionRepository {
                return $container->get(EnvironmentDetector::class)->isProvidingWebsite()
                    ? $container->get(WordPressDatabaseDegreeProgramCollectionRepository::class)
                    : $container->get(WordPressApiDegreeProgramViewRepository::class);
            },
        ];
    }

    /**
     * @return array<string, callable(ContainerInterface):mixed>
     */
    private function makeDatabaseViewRepositoryDefinition(): array
    {
        return [
            ConditionalFieldsFilter::class => static fn() => new ConditionalFieldsFilter(),
            FacultyRepository::class => static fn() => new FacultyRepository(),
            HtmlDegreeProgramSanitizer::class =>
                static fn(): DegreeProgramSanitizer => new HtmlDegreeProgramSanitizer(),
            DegreeProgramRepository::class => static fn(ContainerInterface $container) => new WordPressDatabaseDegreeProgramRepository(
                $container->get(IdGenerator::class),
                $container->get(EventDispatcherInterface::class),
                $container->get(HtmlDegreeProgramSanitizer::class),
                $container->get(CampoKeysRepository::class),
            ),
            WordPressDatabaseDegreeProgramViewRepository::class => static fn(ContainerInterface $container) => new WordPressDatabaseDegreeProgramViewRepository(
                $container->get(DegreeProgramRepository::class),
                $container->get(HtmlDegreeProgramSanitizer::class),
                $container->get(ConditionalFieldsFilter::class),
                $container->get(FacultyRepository::class),
            ),
        ];
    }
}
