<?php

namespace App\Console\Commands;

use App\Currency;
use Illuminate\Console\Command;

class CurrencySync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Currency data with CBAR';

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
        $currency = $this->currency();
        $ytl = $currency['ValType'][1]['Valute'][38]['Value'];
        $usd = $currency['ValType'][1]['Valute'][0]['Value'];
        $currency = Currency::first();
        $currency->tl = $ytl;
        $currency->usd = $usd;
        $currency->update();
        $this->info('Currency Data updated at'.date('d.m.Y', strtotime('now')));
    }

    private function currency()
    {
        $date = date('d.m.Y', strtotime('now'));
        $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, 'https://www.cbar.az/currencies/'.$date.'.xml');
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }
}
