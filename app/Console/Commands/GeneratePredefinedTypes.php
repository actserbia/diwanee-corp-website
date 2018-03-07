<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Node\ClassGenerator\NMPageClassGenerator;
use \App\Models\Node\ClassGenerator\NMQueueClassGenerator;
use \App\NodeType;

class GeneratePredefinedTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nmtype:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $page = new NodeType();
        $page->name = 'Page';
        $pageModel = new NMPageClassGenerator($page);
        $pageModel->generate();

        $queue = new NodeType();
        $queue->name = 'Queue';
        $queueModel = new NMQueueClassGenerator($queue);
        $queueModel->generate();
    }
}
