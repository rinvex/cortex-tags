<?php

declare(strict_types=1);

namespace Cortex\Tags\Console\Commands;

use Illuminate\Console\Command;
use Cortex\Tags\Database\Seeders\CortexTagsSeeder;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cortex:seed:tags')]
class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Tags Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('db:seed', ['--class' => CortexTagsSeeder::class]);

        $this->line('');
    }
}
