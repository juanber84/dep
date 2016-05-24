<?php

namespace Juanber84\Services;

class DownloadService
{
    public function download($url)
    {
        // Todo: refactor and include in configuration
        $pathExec = "/usr/local/bin";
        try {
            $content = file_get_contents($url);
            file_put_contents($pathExec."/newdep.phar", $content);
            unlink($pathExec.'/dep.phar');
            $content = file_get_contents($pathExec."/newdep.phar");
            file_put_contents($pathExec."/dep.phar", $content);
            unlink($pathExec.'/newdep.phar');
        } catch (\Exception $e){
            return false;
        }

        return true;
    }
}