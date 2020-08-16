<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * Message
 *
 * Class definition for OSDI Message Objects
 */
class Message extends AbstractResource
{
    /** @var string targets osdi href */
    public $targets;

    public $from;

    public $replyTo;

    public $subject;

    public $body;

    public $wrapper;

    public $map = [
        'id' => '_id',
    ];

    public function setup()
    {
        //
    }

    /**
     * Schema reference
     */
    protected function schema()
    {
        return [
            "identifiers" => ["my_email_system:1"],
            "subject" => "Stop doing the bad thing",
            "body" => "<p>The mayor should stop doing the bad thing.</p>",
            "from" => "Progressive Action Now",
            "reply_to" => "jane@progressiveactionnow.org",
            "targets" => [
                ["href" => "https://actionnetwork.org/api/v2/queries/2cba37d8-1fbf-11e7-8cc2-22000aedd9ed"]
            ],
            "_links" => [
                "osdi:wrapper" => [
                    "href" => "https://actionnetwork.org/api/v2/wrappers/c945d6fe-929e-11e3-a2e9-12313d316c29",
                ],
            ]
        ];
    }
}
