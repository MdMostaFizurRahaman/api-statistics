<?php

namespace App\Http\Controllers;

use App\Statistic;
use phpseclib\Net\SFTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParseController extends Controller
{
    public function parse()
    {
        $file =null;

        $sftp = new SFTP('192.206.45.108:8822');

        if (!$sftp->login('root', 'APIforGeolocation@32123##')) {
            throw new Exception('Login failed');
        }else{
            $sftp->chdir('/var/www/html/location-api/storage/logs/api/');

            $files = $sftp->nlist();

            $files = array_diff($files, ['.', '..']);

            // print_r($files);

            foreach($files as $file){
                DB::table('processes')->insertOrIgnore([
                         'file_name' => $file,
                         'status' => 0,
                ]);
             }

             $file_name = DB::table('processes')->where('status', 0)->pluck('file_name')->first();
             $sftp->get($file_name, 'test.log');
 
             DB::table('processes')->where('file_name', $file_name)->update([
                 'status' => 2
             ]);

             $this->insertIntoDB($file_name);
        }

    }

    public function insertIntoDB($file_name){

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
                ]);
            }

            DB::table('processes')->where('file_name', $file_name)->update([
                'status' => 1,
            ]);
            
        }else{
            return response("0", 404);  
        }

    }
}
