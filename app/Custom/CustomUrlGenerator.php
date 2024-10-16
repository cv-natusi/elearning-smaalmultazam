<?php
namespace App\Custom;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use CLog;
use Illuminate\Support\Facades\Log;

// use ...

class CustomUrlGenerator extends UrlGenerator
{
    private $logPath = 'custom/custom.log';
    public function route($name, $parameters = [], $absolute = true)
    {
        try {
            return parent::route($name, $parameters, $absolute);
        } catch (RouteNotFoundException $e) {
            $request = new Request;
            if (App::environment('local')) {
                // $request->merge([
                //     "log_payload"=>[
                //         "file" => $e,
                //         "message" => $e->getMessage(),
                //         "line" => $e->getLine(),
                //         "url" => $request->getRequestUri(),
                //         "log_path" => $this->logPath,
                //     ]
                // ]);
                // CLog::catchError($request);
                // Log::info($e);
                return '';
            } else {
                throw $e;
            }
        }
    }
}
