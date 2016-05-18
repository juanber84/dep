<?php

namespace Juanber84\Services;

class GitHubService
{
    const URL = 'https://api.github.com/repos/juanber84/dep/releases/latest';

    public function lastestRelease()
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_USERAGENT,'Awesome-Octocat-App');
        $data = curl_exec($ch);
        curl_close($ch);

        $latestRelease = json_decode($data, true);

        return $latestRelease;
    }

    public function lastestTimeVersion()
    {
        $latestRelease = $this->lastestRelease();
        $latestVersion = strtotime($latestRelease['created_at']);

        return $latestVersion;
    }
}