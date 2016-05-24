<?php

namespace Juanber84\Services;

class DatabaseService
{
    const DIRECTORY = '.dep';
    const DB = 'db.json';

    public function getProjects()
    {
        $db = file_get_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);
        if (!is_array($jsonDb)) {
            throw new \RuntimeException('$jsonDb must be an array.');
        }

        return $jsonDb;
    }

    public function removeProject($keyProject)
    {
        $jsonDb = $this->getProjects();

        if (isset($jsonDb[$keyProject])){
            unset($jsonDb[$keyProject]);
            return true;
        } else {
            return false;
        }
    }
}