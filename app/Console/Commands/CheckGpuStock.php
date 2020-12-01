<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CheckGpuStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpu:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check gpu stock';

    const LINKS = [
        'pny-geforce-rtx-3080-vcg308010tfxppb/p/N82E16814133809',
        'gigabyte-geforce-rtx-3080-gv-n3080eagle-oc-10gd/p/N82E16814932330',
        'gigabyte-geforce-rtx-3080-gv-n3080eagle-10gd/p/N82E16814932367',
        'gigabyte-geforce-rtx-3080-gv-n3080vision-oc-10gd/p/N82E16814932337',
        'msi-geforce-rtx-3080-rtx3080-suprim-x-10g/p/N82E16814137609',
        'evga-geforce-rtx-3080-10g-p5-3881-kr/p/N82E16814487522',
        'gigabyte-geforce-rtx-3080-gv-n3080gaming-oc-10gd/p/N82E16814932329',
        'msi-geforce-rtx-3080-rtx-3080-ventus-3x-10g-oc/p/N82E16814137598',
        'gigabyte-geforce-rtx-3080-gv-n3080aorus-x-10gd/p/N82E16814932345',
        'evga-geforce-rtx-3080-10g-p5-3895-kr/p/N82E16814487519',
        'evga-geforce-rtx-3080-10g-p5-3883-kr/p/N82E16814487521',
        'gigabyte-geforce-rtx-3080-gv-n3080aorus-m-10gd/p/N82E16814932336',
        'msi-geforce-rtx-3080-rtx-3080-ventus-3x-10g/p/N82E16814137600',
        'asus-geforce-rtx-3080-tuf-rtx3080-o10g-gaming/p/N82E16814126452',
        'evga-geforce-rtx-3080-10g-p5-3897-kr/p/N82E16814487518',
        'asus-geforce-rtx-3080-tuf-rtx3080-10g-gaming/p/N82E16814126453',
        'asus-geforce-rtx-3080-rog-strix-rtx3080-o10g-gaming/p/N82E16814126457',
        'msi-geforce-rtx-3080-rtx-3080-gaming-x-trio-10g/p/N82E16814137597',
        'evga-geforce-rtx-3080-10g-p5-3885-kr/p/N82E16814487520',
    ];

    const WAIT_SECONDS = 2;

    const NEWEGG_NO_STOCK_KEYWORD = 'OUT OF STOCK.';

    const USER_ID_TO_NOTIFY = 1;

    private $neweggClient;

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
        $this->neweggClient = new Client(['base_uri' => 'https://www.newegg.com/']);

        foreach (self::LINKS as $link) {
            $response = $this->neweggClient->get($link);
            $html = $response->getBody()->getContents();
            if (strpos($html, self::NEWEGG_NO_STOCK_KEYWORD) !== false) {
                $this->notify($link);
            }
            sleep(self::WAIT_SECONDS);
        }

        $this->info('GPU check is finished');
    }

    private function notify($link)
    {
        $this->info('GPU stock notify is sent');
        Artisan::call("telegram:send-plain-msg", ['user' => self::USER_ID_TO_NOTIFY, 'message' => 'GPU in stock - ' . $link]);
    }
}
