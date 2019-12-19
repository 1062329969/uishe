<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetPhotos extends Command
{
    const Pexels_Key = '563492ad6f91700001000001c412d9cbdfd345a19d33b97ecc12f3e1';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get_photos';

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
        Log::info('common test');

        //
//        $pexels = new Pexels(self::Pexels_Key);
//        $img = $pexels->get_photos(3353994);
//        $img_list = json_decode($img->getBody())->photos;
//        Log::info(json_encode($img_list));
//        return response()->json($img_list);
    }
}
