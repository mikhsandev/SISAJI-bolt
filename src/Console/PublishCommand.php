<?php

namespace LaraZeus\Bolt\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wind:publish {--force : Overwrite any existing files}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PublishCommand all Zeus and Wind components and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // art vendor:publish --tag=zeus-wind-migrations
        // publish Wind files
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-bolt-config', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-bolt-views', '--force' => $this->option('force') ?? false]);

        $this->callSilent('vendor:publish', ['--tag' => 'zeus-bolt-migrations', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-bolt-seeder', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-bolt-factories', '--force' => $this->option('force') ?? false]);

        // publish Zeus files
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-config', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-views', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-assets', '--force' => $this->option('force') ?? false]);
        $this->callSilent('vendor:publish', ['--tag' => 'zeus-lang', '--force' => $this->option('force') ?? false]);

        $this->output->success('Zeus and Bolt has been Published successfully');
    }
}
