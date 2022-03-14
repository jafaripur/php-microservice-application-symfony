<?php

declare(strict_types=1);

namespace App\Queue\Processor\User\Topic;

use App\Queue\Processor\User\UserTopic;
use Araz\MicroService\Processors\RequestResponse\RequestTopic;

final class UserCreatedTopic extends UserTopic
{
    public function execute(RequestTopic $request): void
    {
    }

    public function getTopicName(): string
    {
        return 'user_changed';
    }

    public function getRoutingKeys(): array
    {
        return [
            'user_topic_create',
            'user_topic_update',
        ];
    }

    public function getQueueName(): string
    {
        return sprintf('%s.user_created_topic', $this->getQueue()->getAppName());
    }
}
