<?php
use App\Models\AppList;
if (!function_exists('app_password')) {
    function app_password($app_name)
    {
        $apps = AppList::where('app_id',$app_id)->first()->app_password;
        return $app_password;
    }
}
?>