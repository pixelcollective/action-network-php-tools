<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\OSDI\OSDIObject;

/**
 * Petition
 *
 * Class definition for OSDI Petition Objects
 */
class Petition extends OSDIObject
{
    /**
     * Make petition object
     *
     * @param Object $raw
     * @return \TinyPixel\ActionNetwork\OSDI\Petition
     */
    public function make(object $raw): Petition
    {
        $this->set('raw', $raw);
        $this->set('title', $raw->title);
        $this->set('description', $raw->description);
        $this->set('text', $raw->petition_text);
        $this->set('signatures', $raw->total_signatures);

        $this->set('id',
            $this->collection::make($raw->identifiers)
                ->map(function ($id) {
                    return $this->removeOSDIPrefix($id);
                })->first()
        );

        return $this;
    }

    public function values()
    {
        return $this->values;
    }
}
