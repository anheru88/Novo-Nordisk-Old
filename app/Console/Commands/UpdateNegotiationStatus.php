<?php

namespace App\Console\Commands;

use App\Negotiation;
use Illuminate\Console\Command;

class UpdateNegotiationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:negotiation-status-defeated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update all status of negotiations defeated';

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
        $negotiations = Negotiation::where('status_id', '<=', 6)->where('negotiation_date_end', '<', now())->get();

        foreach ($negotiations as $negotiation) {
            $negotiation->status_id = 9;
            $negotiation->save();
        }


    }
}
