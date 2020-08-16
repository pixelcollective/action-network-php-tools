<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * Form
 *
 * Class definition for OSDI Form Objects
 */
class Form extends AbstractResource
{
    /** @var array properties */
    public $map = [
        'setTitle' => 'title',
    ];

    /**
     * Setup.
     */
    public function setup()
    {
        //
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}
