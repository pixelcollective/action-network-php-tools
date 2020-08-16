<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * ActionNetwork\OSDI\Submission
 *
 * Class definition for OSDI Submission Objects
 */
class Submission extends AbstractResource
{
    /** @var string id */
    public $id;

    /** @var links */
    public $links;

    /** @var string created */
    public $created;

    /** @var string modified */
    public $modified;

    /** @var object referrer */
    public $referrer;

    /**
     * Setup
     */
    public function setup()
    {
        $this->links = $this->collection::make();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links): void
    {
        $this->links = $links;
    }

    public function setPerson($person): void
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
    }
}
