<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QBOPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'QBOPayments:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves QBO payments daily';

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
        QuickBookService::getInstance()->get_daily_payments();
        $this->info('Payment Retrieved successfully!');
    }
}
