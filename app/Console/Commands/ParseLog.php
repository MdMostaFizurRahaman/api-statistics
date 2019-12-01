<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Statistic;
use phpseclib\Net\SFTP;

class ParseLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse log file from another server and insert data into database.';

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
        $file_name = null;

        $sftp = new SFTP('192.206.45.108:8822');

        if (!$sftp->login('root', 'APIforGeolocation@32123##')) {
            throw new Exception('Login failed');
        }else{
            $sftp->chdir('/var/www/html/location-api/storage/logs/api/');

            $files = $sftp->nlist();

            $files = array_diff($files, ['.', '..']);

            foreach($files as $file_name){
                $sftp->get($file_name, 'test.log');
                $sftp->delete($file_name, false);
                $this->insertIntoDB();
            }
        }
    }

    public function insertIntoDB()
    {

        $file = fopen('E:\saidul\xampp\htdocs\location-api\storage\logs\api\laravel-2019-12-01.log', "r");

        $pattern= '/\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\]\s\w*.INFO:/';

        if($file){  
            while(($row = fgets($file)) != false) {
                
                $row = preg_replace($pattern, "", $row);

                $row = explode(',', $row);

                $statistic = new Statistic();

                $statistic->insert([
                    'city'          => isset($row[0]) ? $row[0] : null,
                    'country'       => isset($row[1]) ? $row[1] : null,
                    'countryCode'   => isset($row[2]) ? $row[2] : null,
                    'isp'           => isset($row[3]) ? $row[3] : null,
                    'org'           => isset($row[4]) ? $row[4] : null,
                    'query'         => isset($row[5]) ? $row[5] : null,
                    'region'        => isset($row[6]) ? $row[6] : null,
                    'timezone'      => isset($row[7]) ? $row[7] : null,
                    'provider'      => isset($row[8]) ? $row[8] : null,
                    'log_time'      => isset($row[9]) ? $row[9] : null,
                ]);
            }

        }else{
            return response("0", 404);  
        }

    }
}
