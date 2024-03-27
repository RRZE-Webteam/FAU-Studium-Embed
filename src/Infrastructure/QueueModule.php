<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Fau\DegreeProgram\Common\Application\JsonSerializer\JsonSerializer;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
use Fau\DegreeProgram\Common\Application\Queue\MessageHandler;
use Fau\DegreeProgram\Common\Infrastructure\Queue\SyncMessageBus;
use Fau\DegreeProgram\Common\Infrastructure\Queue\WpCronMessageBus;
use Fau\DegreeProgram\Output\Infrastructure\Cache\WarmCacheMessage;
use Fau\DegreeProgram\Output\Infrastructure\Cache\WarmCacheMessageHandler;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\FlushRewriteRulesMessageHandler;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\FlushRewriteRulesMessage;
use Fau\DegreeProgram\Output\Infrastructure\Search\UpdateFilterablePostsMetaMessageHandler;
use Fau\DegreeProgram\Output\Infrastructure\Search\UpdateFilterableTermsMessage;
use Fau\DegreeProgram\Output\Infrastructure\Search\UpdateFilterableTermsMessageHandler;
use Fau\DegreeProgram\Output\Infrastructure\Search\UpdateSearchableContentMessage;
use Fau\DegreeProgram\Output\Infrastructure\Search\UpdateSearchableContentMessageHandler;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\FactoryModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class QueueModule implements ServiceModule, FactoryModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function services(): array
    {
        return [
            JsonSerializer::class => static fn() => new JsonSerializer(),
            MessageHandler::class => static fn(ContainerInterface $container) => new MessageHandler(
                $container->get(JsonSerializer::class),
                [
                    WarmCacheMessage::class => [
                        $container->get(WarmCacheMessageHandler::class),
                    ],
                    UpdateSearchableContentMessage::class => [
                        $container->get(UpdateSearchableContentMessageHandler::class),
                        $container->get(UpdateFilterablePostsMetaMessageHandler::class),
                    ],
                    UpdateFilterableTermsMessage::class => [
                        $container->get(UpdateFilterableTermsMessageHandler::class),
                    ],
                    FlushRewriteRulesMessage::class => [
                        $container->get(FlushRewriteRulesMessageHandler::class),
                    ],
                ],
            ),
            SyncMessageBus::class => static fn(ContainerInterface $container) => new SyncMessageBus(
                $container->get(MessageHandler::class),
            ),
            WpCronMessageBus::class => static fn(ContainerInterface $container) => new WpCronMessageBus(
                $container->get(JsonSerializer::class),
            ),
        ];
    }

    public function factories(): array
    {
        return [
            MessageBus::class => static function (ContainerInterface $container): MessageBus {
                return defined('WP_CLI') && WP_CLI
                    ? $container->get(SyncMessageBus::class)
                    : $container->get(WpCronMessageBus::class);
            },
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            MessageHandler::ID,
            [$container->get(MessageHandler::class), 'handle']
        );

        return true;
    }
}
