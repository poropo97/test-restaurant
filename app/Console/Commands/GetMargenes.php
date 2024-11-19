<?php

namespace App\Console\Commands;
use App\Services\Recipe\RecipeService;

use Illuminate\Console\Command;

class GetMargenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-margenes';

    public function __construct(RecipeService $recipeService)
    {
        parent::__construct();
        $this->recipeService = $recipeService;
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        dd($this->recipeService->getMargenes());

        
    }
}
