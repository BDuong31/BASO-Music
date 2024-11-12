<?php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../services/SpotifyService.php';

class TrendingController
{
    public function index()
    {
        try {
            $spotifyService = new SpotifyService();
            return $spotifyService->getTrendingSongs();
        } catch (Exception $e) {
            echo 'Error in Controller: ' . $e->getMessage();
            exit;
        }
    }
}

