<?php

class ActionNetworkTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath . '/vendor/autoload.php');
    }

    public function bootstrap($projectPath)
    {
        require $projectPath . '/vendor/autoload.php';

        $this->app = (new \TinyPixel\ActionNetwork\Bootstrap(__DIR__ . '/..'))->app();
    }

    public function getAvailableVariables()
    {
        return [
          '__app' => $this->app,
          '__osdi' => $this->app->get('osdi'),
          '__petitions' => $this->app->get('osdi')->get('petitions'),
          '__people' => $this->app->get('osdi')->get('people'),
        ];
    }

    public function contextMenu()
    {
        return [
            Label::create('Detected Action Network'),
        ];
    }
}