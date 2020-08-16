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
          'app' => $this->app,
          'api' => $this->app->get('osdi'),
          'people' => $this->app->get('people'),
          'tags' => $this->app->get('tags'),
        ];
    }

    public function contextMenu()
    {
        return [
            Label::create('Detected Action Network'),
        ];
    }
}