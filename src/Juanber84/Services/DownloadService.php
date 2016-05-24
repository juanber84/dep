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
            file_put_contents($pathExec."/newdep", $content);
            unlink($pathExec.'/dep');
            $content = file_get_contents($pathExec."/newdep");
            file_put_contents($pathExec."/dep", $content);
            unlink($pathExec.'/newdep');
        } catch (\Exception $e){
            return false;
        }

        return true;
    }
}