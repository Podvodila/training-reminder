<?php

namespace App\Console\Commands;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
        //3080
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
        //3070
        'msi-geforce-rtx-3070-rtx-3070-ventus-2x/p/N82E16814137605',
        'evga-geforce-rtx-3070-08g-p5-3753-kr/p/N82E16814487529',
        'evga-geforce-rtx-3070-08g-p5-3765-kr/p/N82E16814487531',
        'gigabyte-geforce-rtx-3070-gv-n3070eagle-oc-8gd/p/N82E16814932343',
        'asus-geforce-rtx-3070-rog-strix-rtx3070-o8g-gaming/p/N82E16814126458',
        'msi-geforce-rtx-3070-rtx-3070-ventus-2x-oc/p/N82E16814137602',
        'asus-geforce-rtx-3070-dual-rtx3070-o8g/p/N82E16814126459',
        'gigabyte-geforce-rtx-3070-gv-n3070eagle-8gd/p/N82E16814932344',
        'evga-geforce-rtx-3070-08g-p5-3751-kr/p/N82E16814487528',
        'asus-geforce-rtx-3070-dual-rtx3070-8g/p/N82E16814126460',
        'asus-geforce-rtx-3070-ko-rtx3070-o8g-gamin/p/N82E16814126466',
        'gigabyte-geforce-rtx-3070-gv-n3070vision-oc-8gd/p/N82E16814932360',
        'gigabyte-geforce-rtx-3070-gv-n3070aorus-m-8gd/p/N82E16814932359',
        'gigabyte-geforce-rtx-3070-gv-n3070gaming-oc-8gd/p/N82E16814932342',
        'msi-geforce-rtx-3070-rtx-3070-ventus-3x-oc/p/N82E16814137601',
        'evga-geforce-rtx-3070-08g-p5-3755-kr/p/N82E16814487530',
        'asus-geforce-rtx-3070-tuf-rtx3070-o8g-gaming/p/N82E16814126461',
        'evga-geforce-rtx-3070-08g-p5-3767-kr/p/N82E16814487532',
        'msi-geforce-rtx-3070-rtx-3070-gaming-x-trio/p/N82E16814137603',
        //3080 bundle
        'Product/ComboDealDetails?ItemList=Combo.4208292',
        'Product/ComboDealDetails?ItemList=Combo.4207521',
        'Product/ComboDealDetails?ItemList=Combo.4190359',
        'Product/ComboDealDetails?ItemList=Combo.4207523',
        'Product/ComboDealDetails?ItemList=Combo.4207751',
        'Product/ComboDealDetails?ItemList=Combo.4191565',
    ];

    const NEWEGG_BASE_URI = 'https://www.newegg.com/';

    const WAIT_SECONDS = 2;

    const NEWEGG_STOCK_KEYWORD = 'In stock';

    const USER_ID_TO_NOTIFY = 1;

    const BUNDLE_ITEM_URL_TEMPLATE = 'Product/ComboDealDetails?ItemList=Combo';

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
        $neweggClient = HttpClient::create();

        foreach (self::LINKS as $link) {
            $response = $neweggClient->request('GET', self::NEWEGG_BASE_URI . $link);
            $html = $response->getContent();
            $crawler = new Crawler($html);
            $stockInfo = $crawler->filter($this->getSelector($link))->text('node not found');
            $this->info('Info - ' . $stockInfo);
            if (strpos($stockInfo, self::NEWEGG_STOCK_KEYWORD) !== false) {
                $this->notify($link);
            }
            sleep(self::WAIT_SECONDS);
        }

        $this->info('GPU check is finished');
    }

    private function notify($link)
    {
        $this->info('GPU stock notify is sent');
        Artisan::call("telegram:send-plain-msg", [
            'user' => self::USER_ID_TO_NOTIFY,
            'msg' => 'GPU in stock - ' . self::NEWEGG_BASE_URI . $link,
        ]);
    }

    private function getSelector($url)
    {
        if (strpos($url, self::BUNDLE_ITEM_URL_TEMPLATE) !== false) {
            return '.grpDesc.boxConstraint .note';
        }
        return '.product-inventory strong';
    }
}
