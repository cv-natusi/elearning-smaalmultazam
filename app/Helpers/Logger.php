<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use CLog;

class Logger{
	/*
	|--------------------------------------------------------------------------
	| Dokumentasi
	|--------------------------------------------------------------------------
	| --- CLog (alias dari class Logger) ---
	|
	| #Format Method: lokerInfo(loker==nama-modul , info==log-level)
	| #Log Level:
	|		~> Debug: "simpan hasil debugging"
	|		~> Info: "log hasil request client yang sukses(200-level http response code)"
	|		~> Warning: "client melakukan request dan hasilnya not found"
	|		~> Error: "ketika terdapat error pada code atau transaksi database(undefined variable, undefined column, dst..)"
	|
	| #Setup Payload:
	|	1. Buat payload berupa array dengan format (key:value)
	|		[
	|			"url"      => "url(route)",
	|			"file"     => "lokasi-file",
	|			"method"   => "nama-function-controller",
	|			"message"  => "pesan(buat se-informatif mungkin)",
	|			"line"     => "baris-error-code(hanya di set ketika log level ERROR)",
	|			"data"     => "parameter dari request",
	|			"log_path" => "directory untuk simpan log file",
	|		]
	|	2. Tambahkan payload-array yang sudah dibuat kedalam request object dengan key "log_payload" dengan cara(pilih salah satu):
	|		a. $request->merge([log_payload => payload-array]);
	|		b. $request->log_payload = payload-array;
	|
	| #Penggunaan:
	|	1. Tambahkan code "use CLog" pada controller yang ingin menggunakan sistem logging
	|	2. Panggil method dan tambahkan request object kedalam parameter ~> CLog::lokerInfo($request)
	*/
		
	public static function setPayload($request){
		$keyForLog = ['url','file','method','message','line','data']; # Declar key param log, tambahkan value di baris ini jika ingin menambah parameter untuk log
		$payload = [];
		if(in_array($request->log_level,['debug','info'])){
			$keyForLog = ['method','message','data']; # Gunakan key ini jika log level(debug/info)
		}
		# Modify params start
		foreach($keyForLog as $k => $v){
			if(isset($request->log_payload[$v])){
				$message = $request->log_payload[$v];
			}else{
				switch ($v) {
					case 'url': $message='URL NOT SET'; break;
					case 'file': $message='FILE NOT SET'; break;
					case 'message': $message='MESSAGE NOT SET'; break;
					default: $message='-'; break;
				}
			}
			if($message!=='-'){
				$payload[$v] = $message;
			}
		}
		# Modify params end
		return $request->merge([ # Add variable log_payload to request object
			'log_payload' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
		]);
	}
	public static function setLogBuild($request){ # Custom log channels
		Log::info($request->all());
		$logPath = $request->log_path ? $request->log_path : 'default/default.log';
		CLog::setPayload($request); # Setup data payload untuk di log
		$request->request->remove('log_path');
		$request->request->remove('log_level');
		return Log::build([
			'driver' => 'daily',
			'path' => storage_path("logs/$logPath"),
			'days' => 7, # Max 7 file per-folder-level(debug=7 file max,info=7 file max, dst..) jika sudah max 7 file, replace file terlama
			'permission' => 0664, # Default permission 0644
		]);
	}
	
	public static function catchError($request){
		$request->merge([ # Add variable log_payload to request object\
			'log_path'=>isset($request->log_payload['log_path'])?$request->log_payload['log_path']:'',
			'log_level'=>'error',
		]);
		CLog::setLogBuild($request)->error($request->log_payload); # Jalankan log
		$request->request->remove('log_payload');
	}


	public static function systemError($request){
		$request->merge([ # Add variable log_payload to request object
			'log_path' => 'system.log',
			'log_level' => 'error',
		]);
		CLog::setLogBuild($request)->error($request->log_payload); # Jalankan log
		$request->request->remove('log_payload');
	}
}
