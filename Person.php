<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

/**
 * ActionNetwork\Person
 *
 * Class definition for OSDI Person Objects
 *
 * @package    TinyPixel\ActionNetwork\Person
 * @copyright  2019, Tiny Pixel Collective LLC
 * @author     Kelly Mears     <developers@tinypixel.io>
 * @author     Jonathan Kissam <jonathankissam.com>
 * @license    MIT
 * @link       https://github.com/pixelcollective/action-network-toolkit
 * @see        https://actionnetwork.org/docs
 *
 **/

// TODO: Finish refactoring class
class Person extends ActionNetwork
{
    /**
     * Last name
     *
     * @var string
     */
    public $family_name;

    /**
     * First name
     *
     * @var string
     */
    public $given_name;

    /**
     * Postal codes
     *
     * @var array
     */
    public $postal_addresses = [];

    /**
     * Email addresses
     *
     * @var array
     */
    public $email_addresses = [];

    /**
     * Custom fields
     *
     * @var array
     */
    public $custom_fields;

    /**
     * Valid Subscription Statuses
     *
     * @var array
     */
    private $valid_subscription_statuses = [
        'subscribed',
        'unsubscribed',
        'bouncing',
        'spam complaint'
    ];

    /**
     * Valid address fields
     *
     * @var array
     */
    private $valid_address_fields = [
        'primary',
        'address_lines',
        'locality',
        'region',
        'postal_code',
        'country'
    ];

    /**
     * __construct
     *
     * @param mixed $person
     *
     * @return void
     */
    public function __construct($person = null)
    {
        if (!null($person)) {
            $this->setup($person);
        }

        return $this;
    }

    /**
     * Setup
     *
     * @param mixed $person
     *
     */
    public function setup($person = null)
    {
        $person = $this->formatAsObject($person);

        $this->setupEmail($person);
        $this->validate();
        $this->setFamilyName($person->family_name);
        $this->setGivenName($person->given_name);
    }

    /**
     * Set Tag
     *
     * @param mixed $activist
     * @param mixed $tags
     *
     * @return void
     */
    public function addTag($activist, $tags = null)
    {
        $activist->tags[] = is_array($tags) ? $tags : null;

        return $this;
    }

    /**
     * Set Comment
     *
     * @param mixed $activist
     * @param mixed $comment
     *
     * @return void
     */
    public function addComment($activist, $comment = null)
    {
        $activist->comments = isset($comment) ? $comment : null;

        return $this;
    }

    /**
     * Set Status
     *
     * @param mixed $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->email_addresses[0]->status = in_array(
            $status,
            $this->valid_subscription_statuses
        ) ? $status : false;

        return $this;
    }

    /**
     * set Last Name
     *
     * @param mixed $family_name
     *
     * @return void
     */
    public function setLastName($last_name = null)
    {
        $this->family_name = isset($last_name) ? $last_name :
            ActionNetwork::error('problem with family name');

        return $this;
    }

    /**
     * set First Name
     *
     * @param mixed $given_name
     *
     * @return void
     */
    public function setFirstName($first_name = null)
    {
        $this->given_name = isset($first_name) ? $first_name :
            ActionNetwork::error('problem with first name');

        return $this;
    }

    /**
     * setPostalAddress
     *
     * @param mixed $address
     *
     * @return void
     */
    public function setAddress($address = null)
    {
        $address = $this->formatAsObject($address);

        $valid_address = new stdClass();

        foreach ($this->valid_address_fields as $field) {
            $valid_address->$field = $this->validateAddressField(
                $address,
                $field
            ) ? $address->$field : null;
        }

        $this->postal_addresses[] = $valid_address;

        return $this;
    }

    /**
     * setCustom
     *
     * @param mixed $key_or_array
     * @param mixed $value
     *
     * @return void
     */
    public function setCustom($key_or_array, $value = null)
    {
        if (is_array($key_or_array)) {
            foreach ($key_or_array as $k => $v) {
                $this->custom_fields->$k = $v;
            }
        } elseif (is_string($key_or_array) && $value) {
            $this->custom_fields->$key_or_array = $value;
        }

        return $this;
    }

    /**
     * Validate address field with typecheck
     * on 'address_lines'
     *
     * @param mixed $address
     * @param mixed $field
     *
     * @return void
     */
    private function validateAddressField($address, $field)
    {
        if (isset($address->$field)) {
            return $field=='address_lines' ? is_array($address->$field) : true;
        }

        return false;
    }

    /**
     * Validate
     *
     * @param object $person
     * @return void
     */
    public function validate()
    {
        $no_email = !isset($this->person->email_addresses[0]->address);
        $invalid_email = self::checkEmail($this->person->email_addresses[0]->address);

        if ($no_email || $invalid_email) {
            ActionNetwork::error('invalid email or no email supplied');
        }

        return $this;
    }

    /**
     * setup Email
     *
     * @param mixed $person
     * @return void
     */
    private function setupEmail($person)
    {
        if (isset($person->email_addresses) && is_array($person->email_addresses)) {
            $this->setEmails($person->email_addresses);
        }

        ActionNetwork::error('invalid data passed to setupEmail method');
    }

    /**
     * set Emails
     *
     * @param mixed $emails
     * @return void
     */
    public function setEmails($emails)
    {
        if (isset($emails) && is_array($emails)) {
            foreach ($emails as $index => $email) {
                $this->setEmail($email, $index);
            }
        } else {
            ActionNetwork::error('invalid data passed to setEmails method');
        }

        return $this;
    }

    /**
     * set Email
     *
     * @param [type] $email
     * @param integer $index
     * @return void
     */
    public function setEmail($email, $index = 0)
    {
        $this->email_addresses[$index] = isset($email) && self::checkEmail($email) ??
            ActionNetwork::error('invalid email passed to setEmail method');

        return $this;
    }

    /**
     * format as Object
     *
     * @param [type] $person
     * @return void
     */
    private function formatAsObject($person)
    {
        $person = (is_array($person)) ? (object) $person : null;

        return is_object($person) ? $person : ActionNetwork::error(
            'person must be passed as an associative array or object'
        );
    }

    /**
     * check Email
     *
     * @param string $email
     * @return void
     */
    public static function checkEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }
}
