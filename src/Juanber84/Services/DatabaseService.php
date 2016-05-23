<?php

namespace Juanber84\Services;

class DatabaseService
{
    const DIRECTORY = '.dep';
    const DB = 'db.json';

    public function getProjects()
    {
        $db = file_get_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);

        return $jsonDb;
    }
}