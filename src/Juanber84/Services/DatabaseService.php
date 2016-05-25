<?php

namespace Juanber84\Services;

class DatabaseService
{
    const DIRECTORY = '.dep';
    const DB = 'db.json';

    public function getProjects()
    {
        $this->generateDatabase();
        if (!file_exists(getenv("HOME").'/'.self::DIRECTORY)) {
            mkdir(getenv("HOME").'/'.self::DIRECTORY, 0777, true);
        }

        $db = file_get_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);
        if (!is_array($jsonDb)) {
            throw new \RuntimeException('$jsonDb must be an array.');
        }

        return $jsonDb;
    }

    public function removeProject($keyProject)
    {
        $this->generateDatabase();
        $jsonDb = $this->getProjects();

        if (isset($jsonDb[$keyProject])){
            unset($jsonDb[$keyProject]);
            try {
                file_put_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB, json_encode($jsonDb));
            } catch (\Exception $e){
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function addProject($keyProject, $path)
    {
        $this->generateDatabase();
        $jsonDb = $this->getProjects();
        $jsonDb[$keyProject] = $path;

        try {
            file_put_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB, json_encode($jsonDb));
        } catch (\Exception $e){
            return false;
        }

        return true;
    }

    private function generateDatabase()
    {
        if (!file_exists(getenv("HOME").'/'.self::DIRECTORY)) {
            try {
                mkdir(getenv("HOME").'/'.self::DIRECTORY, 0777, true);
            } catch (\Exception $e) {
            }
            file_put_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB, json_encode('{[]}'));
        }
    }

}