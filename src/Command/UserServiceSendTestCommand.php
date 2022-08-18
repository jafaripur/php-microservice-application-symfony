<?php

declare(strict_types=1);

namespace App\Command;

use Araz\Service\User\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserServiceSendTestCommand extends Command
{
    protected static $defaultName = 'user-service:send-test';

    protected static $defaultDescription = 'Listen to user service';

    public function __construct(
        private UserService $userService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Sending async command to UserService::getUserInformation()');
        $userAsyncCommands = $this->userService->commands()->async(5000)
            ->getUserInformation(['id' => '123'], 'cor-test-1234', 2000)
            ->getUserInformation(['id' => '123'], 'cor-test-1235', 2000)
            ->getUserInformation(['id' => '123'], 'cor-test-1236', 2000)
        ;

        $output->writeln("Sending command to CommandSender::getUserInformation()\n");
        $response = $this->userService->commands()->getUserInformation(['id' => '123']);
        $output->writeln(print_r($response->getBody(), true) . "\n\n");

        $output->writeln("Sending emit to EmitSender::userLoggedIn()\n");
        $msgId = $this->userService->emits()->userLoggedIn(['id' => '123']);
        $output->writeln(sprintf('Emit message ID: %s', (string)$msgId));

        $output->writeln(sprintf('Sending topic to TopicSender::userLoggedIn() with routing key: %s', $this->userService->topics()->getRoutingKeyUserTopicCreate()));
        $msgId = $this->userService->topics()->userChanged($this->userService->topics()->getRoutingKeyUserTopicCreate(), ['id' => '123']);
        $output->writeln(sprintf('Topic message ID: %s', (string)$msgId));

        $output->writeln(sprintf('Sending topic to TopicSender::userLoggedIn() with routing key: %s', $this->userService->topics()->getRoutingKeyUserTopicUpdate()));
        $msgId = $this->userService->topics()->userChanged($this->userService->topics()->getRoutingKeyUserTopicUpdate(), ['id' => '123']);
        $output->writeln(sprintf('Topic message ID: %s', (string)$msgId));

        $output->writeln('Sending worker to WorkerSender::userProfileAnalysis()');
        $msgId = $this->userService->workers()->userProfileAnalysis(['id' => '123']);
        $output->writeln(sprintf('Worker message ID: %s', (string)$msgId));

        $output->writeln('Sending worker to WorkerSender::userProfileUpdateNotification()');
        $msgId = $this->userService->workers()->userProfileUpdateNotification(['id' => '1234']);
        $output->writeln(sprintf('Worker message ID: %s', (string)$msgId));

        $output->writeln('Receiving async command from UserService::getUserInformation ...');
        foreach ($userAsyncCommands->receive() as $correlationId => $response) {
            $output->writeln(print_r([$correlationId => $response->getBody()], true));
        }

        return Command::SUCCESS;
    }
}
