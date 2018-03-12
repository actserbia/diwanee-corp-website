<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Node\ClassGenerator\NMPageClassGenerator;
use \App\Models\Node\ClassGenerator\NMQueueClassGenerator;
use \App\Models\Node\ClassGenerator\GraphQLTypeClassGenerator;
use \App\Models\Node\ClassGenerator\GraphQLQueryClassGenerator;

use \App\NodeType;
use \App\NodeModel\NmPage;
use \App\NodeModel\NmQueue;


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
    protected $description = 'Generate predefined types (Page and Queue)';

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
        $page =  NodeType::with('fields', 'attribute_fields')->where('name', 'like', 'Page')->first();
        $pageModel = new NMPageClassGenerator($page);
        $pageModel->generate();
        $graphQlTypeClassGenerator = new GraphQLTypeClassGenerator($page);

        $graphQlTypeClassGenerator->generate();

        $graphQlQueryClassGenerator = new GraphQLQueryClassGenerator($page);
        $graphQlQueryClassGenerator->generate();

        $queue = NodeType::with('fields', 'attribute_fields')->where('name', 'like', 'Queue')->first();
        $queueModel = new NMQueueClassGenerator($queue);
        $queueModel->generate();
        $graphQlTypeClassGenerator = new GraphQLTypeClassGenerator($queue);
        $graphQlTypeClassGenerator->generate();

        $graphQlQueryClassGenerator = new GraphQLQueryClassGenerator($queue);
        $graphQlQueryClassGenerator->generate();
    }
}
