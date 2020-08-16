<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * Petition
 *
 * Class definition for OSDI Petition Objects
 */
class Petition extends AbstractResource
{
    /** @var array properties */
    public $map = [
        'setId' => '_links',
        'setTitle' =>'title',
        'setDescription' =>'description',
        'setSignatures' =>'signatures',
        'setText' => 'text',
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

    public function setSignatures($signatures)
    {
        $this->signatures = $signatures;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}
