<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * ActionNetwork\OSDI\Person
 *
 * Class definition for OSDI Person Objects
 *
 */
class Person extends AbstractResource
{
    public $id;

    public $name;

    public $emails;

    public $addresses;

    public function setup()
    {
        $this->name = (object) [
            'first' => null,
            'last' => null,
        ];
    }

    public function setId($links)
    {
        $this->id = $links->get('self');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFirstName($name)
    {
        $this->name->first = $name;
    }

    public function getFirstName()
    {
        return $this->name->first ?? null;
    }

    public function setLastName($name)
    {
        $this->name->last = $name;
    }

    public function getLastName()
    {
        return $this->name->last ?? null;
    }

    public function setEmails($emails)
    {
        $this->emails = $emails;
    }

    public function getEmails()
    {
        return $this->emails;
    }

    public function getEmail()
    {
        return $this->emails->first()->address;
    }

    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function getAddress()
    {
        return $this->addresses->first()->address;
    }

    public function getLink($link)
    {
        return $this->links[$link];
    }

    public function getLinks()
    {
        return property_exists($this, 'links') ? $this->links : null;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}
