<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvoiceHistory;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceSent;
use Carbon\Carbon;

class InvoiceSentClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sent:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client remaining invoice';

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
        $invoices = Invoice::where('sending_type', 'recurring')->select('id')->get()->toArray();
        $invoice_histories = InvoiceHistory::withTrashed('invoice')->whereIn('invoice_id', $invoices)->where('is_paid', 0)->get();
        foreach($invoice_histories as $history){
            $last_mailing_time = Carbon::parse($history->last_mailing_time)->addDays($history->invoice->recurring_period);
            if(Carbon::now() >= $last_mailing_time){
                $invoice = Invoice::find($history->invoice_id);
                $client = User::find($history->client_id);
                Mail::to($client->email)->send(new InvoiceSent($invoice));

                $invoice_history = InvoiceHistory::find($history->id);
                $invoice_history->last_mailing_time = Carbon::now();
                $invoice_history->mailing_count = $invoice_history->mailing_count+1;
                $invoice_history->save();
            }
        }
    }
}
