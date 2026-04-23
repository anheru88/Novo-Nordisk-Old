<?php

namespace App\Console\Commands;

use App\Quotation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateQuotationsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:quotation-status-defeated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update all status of quotations defeated';

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
        $quotations = Quotation::where('status_id', '<=', 6)->where('quota_date_end','<', now())->get();

        foreach ($quotations as $quotation){
            $quotation->status_id = 9;
            $quotation->save();
            $details = $quotation->quotadetails;
            foreach ($details as $detail){
                if($detail->is_valid === 6 or $detail->is_valid === 1){
                    $detail->is_valid = 9;
                    $detail->save();
                }
            }
        }
    }
}
