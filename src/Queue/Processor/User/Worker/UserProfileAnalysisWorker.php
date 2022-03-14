<?php

declare(strict_types=1);

namespace App\Queue\Processor\User\Worker;

use App\Queue\Processor\User\UserWorker;
use Araz\MicroService\Processors\RequestResponse\Request;

final class UserProfileAnalysisWorker extends UserWorker
{
    public function execute(Request $request): void
    {
    }

    public function getJobName(): string
    {
        return 'user_profile_analysis';
    }
}
