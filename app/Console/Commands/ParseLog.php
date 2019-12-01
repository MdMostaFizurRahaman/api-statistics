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

        $file = fopen('test.log', "r");

        $pattern= '/\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\]\s\w*.INFO:/';

        if($file){  
            while(($row = fgets($file)) != false) {
                
                $row = preg_replace($pattern, "", $row);

                $row = explode(',', $row);

                $statistic = new Statistic();

                $statistic->insert([
                    'city'          => $row[0],
                    'country'       => $row[1],
                    'countryCode'   => $row[2],
                    'isp'           => $row[3],
                    'org'           => $row[4],
                    'query'         => $row[5],
                    'region'        => $row[6],
                    'timezone'      => $row[7],
                    'provider'      => $row[8],
                    'log_time'      => $row[9],
                ]);
            }

        }else{
            return response("0", 404);  
        }

    }
}
