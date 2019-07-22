<?php

namespace App\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \DB::listen(
            function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }

                // Insert bindings into query
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);

                $query = vsprintf($query, $sql->bindings);

                // Save the query to file
                $logFile = fopen(
                    storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
                    'a+'
                );
                fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
                fclose($logFile);
            }
        );
        Response::macro('success', function($msg = 'SUCCESS', $token = '') {
            return Response::json_unicode(['code' => 0, 'msg' => $msg, 'token' => $token]);
        });

        Response::macro('error', function($code = 500, $msg = 'error', $token = '') {
            return Response::json_unicode(['code' => $code, 'msg' => $msg, 'token' => $token]);
        });

        Response::macro('model', function($model, $token = '') {
            return Response::json_unicode(['code' => 0, 'msg' => 'SUCCESS', 'token' => $token, 'data' => $model->toArray()]);
        });

        Response::macro('array', function($arr, $token = '') {
            return Response::json_unicode(['code' => 0, 'msg' => 'SUCCESS', 'token' => $token, 'data' => $arr]);
        });

        Response::macro('string', function($value, $token = '') {
            return Response::json_unicode(['code' => 0, 'msg' => 'SUCCESS', 'token' => $token, 'data' => $value]);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
