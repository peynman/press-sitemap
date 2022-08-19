<?php

namespace Larapress\Sitemap\Commands;

use Illuminate\Console\Command;
use Larapress\Sitemap\Services\Sitemap\ISitemapService;

class BuildSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:sitemap:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate Sitemap data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var ISitemapService */
        $service = app(ISitemapService::class);
        $service->generateSitemap();
        $this->info('Sitemap updated.');

        return 0;
    }
}
