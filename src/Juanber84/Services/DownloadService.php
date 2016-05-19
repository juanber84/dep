<?php

namespace Juanber84\Services;

class DownloadService
{
    public function download($url)
    {
        try {
            $content = file_get_contents($url);
            file_put_contents("./newdep.phar", $content);
            unlink('./dep.phar');
            $content = file_get_contents("./newdep.phar");
            file_put_contents("dep.phar", $content);
            unlink('./newdep.phar');
        } catch (\Exception $e){
            return false;
        }

        return true;
    }
}