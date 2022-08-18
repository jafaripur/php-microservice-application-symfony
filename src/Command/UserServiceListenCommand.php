<?php

declare(strict_types=1);

namespace App\Command;

use Araz\MicroService\Queue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserServiceListenCommand extends Command
{
    protected static $defaultName = 'user-service:listen';

    protected static $defaultDescription = 'Listen to user service';

    public function __construct(
        private Queue $queue
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('consumers', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Consumer identity to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->queue->getConsumer()->consume(0, (array)$input->getArgument('consumers'));

        return Command::SUCCESS;
    }
}
