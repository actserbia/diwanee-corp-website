<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Node\ClassGenerator\ClassGenerator;
use \App\Models\Node\NodeModelDBGenerator;

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
    protected $description = 'Generate predefined types (Page and Queue)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->generateNodeTypeClasses('Page');
        $this->generateNodeTypeClasses('Queue');
        $this->generateNodeTypeClasses('Tag Data');
    }
    
    private function generateNodeTypeClasses($nodeTypeName) {
        $nodeType =  NodeType::withAll()->where('name', 'like', $nodeTypeName)->first();
        
        $modelDBGenerator = new NodeModelDBGenerator($nodeType);
        $modelDBGenerator->generate();
        
        ClassGenerator::generateAllFilesForNodeType($nodeType);
    }
}
