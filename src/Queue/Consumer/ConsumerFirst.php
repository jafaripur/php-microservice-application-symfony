<?php

declare(strict_types=1);

namespace App\Queue\Consumer;

use Araz\MicroService\ProcessorConsumer;
use Generator;

final class ConsumerFirst extends ProcessorConsumer
{
    public function getConsumerIdentify(): string
    {
        return 'first-consumer';
    }

    /**
     * {@inheritDoc}
     */
    public function getProcessors(): Generator
    {
        // Command
        yield \App\Queue\Processor\User\Command\UserGetInfoCommand::class;

        // Emits
        yield \App\Queue\Processor\User\Emit\UserLoggedInEmit::class;

        // Topics
        yield \App\Queue\Processor\User\Topic\UserCreatedTopic::class;

        // Workers
        yield \App\Queue\Processor\User\Worker\UserProfileAnalysisWorker::class;
        yield \App\Queue\Processor\User\Worker\UserProfileUpdateNotificationWorker::class;
    }
}
