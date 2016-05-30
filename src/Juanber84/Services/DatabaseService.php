<?php

namespace Juanber84\Services;

class DatabaseService
{
    const DIRECTORY = '.dep';
    const DB = 'db.json';

    public function getProjects()
    {
        $this->generateDatabase();
        $db = file_get_contents($this->getDatabasePath());
        
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
                file_put_contents($this->getDatabasePath(), json_encode($jsonDb));
            } catch (\Exception $e){
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    public function addProject($keyProject, $path)
    {
        $this->generateDatabase();
        $jsonDb = $this->getProjects();
        $jsonDb[$keyProject] = $path;

        try {
            file_put_contents($this->getDatabasePath(), json_encode($jsonDb));
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
                throw new \Exception('Problem generation database');
            }
            file_put_contents($this->getDatabasePath(), json_encode(array()));
        }
    }

    private function getDatabasePath()
    {
        return getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB;
    }

}