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
    public $family_name;
    public $given_name;
    public $postal_addresses = array();
    public $email_addresses = array();
    public $custom_fields;

    private $valid_subscription_statuses = [
        'subscribed',
        'unsubscribed',
        'bouncing',
        'spam complaint'
    ];

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
        $person = (is_array($person)) ? (object) $person : void;

        if (!is_object($person)) :
            trigger_error(
                'person must be passed as an associative array or object',
                E_USER_ERROR
            );
        endif;

        if (isset($person->email)
            && filter_var($person->email, FILTER_VALIDATE_EMAIL)
        ) :
                $person->email_addresses[0] =
                    (object) array('address' => $person->email);
        endif;

        if (isset($person->email_addresses)
            && is_array($person->email_addresses)
        ) :
            foreach ($person->email_addresses as $index => $email_address) :
                if (is_array($email_address)) :
                    $person->email_addresses[$index] = (object) $email_address;
                endif;
            endforeach;
        endif;

        if (!isset($person->email_addresses[0]->address)
            || !filter_var(
                $person->email_addresses[0]->address,
                FILTER_VALIDATE_EMAIL
            )
        ) :
            trigger_error(
                'person must include a valid email address',
                E_USER_ERROR
            );
        endif;

        $this->setStatus($this->email_addresses[0]->status);
        $this->setEmailAddress($person->email_address);
        $this->setFamilyName($person->family_name);
        $this->setGivenName($person->given_name);


        foreach ($this->valid_address_fields as $field) :
            if ($field == 'primary') :
                continue;
            endif;

            if (isset($person->$field)) :
                if (!isset($person->address)
                    || !is_object($person->address)
                ) :
                        $person->address = new stdClass();
                endif;

                if ($field == 'address_lines') :
                    $person->address->address_lines = array($person->address_lines);
                else :
                    $person->address->$field = $person->$field;
                endif;
            endif;
        endforeach;

        if (isset($person->address)) :
            $address = $person->address;

            if (is_array($address)) :
                $address = (object) $address;
            endif;

            if (!is_object($address)) :
                trigger_error(
                    'address must be passed as an associative array or object',
                    E_USER_ERROR
                );
            endif;

            if (!isset($address->primary)) :
                $address->primary = true;
            endif;

            $valid_address = new stdClass();

            foreach ($this->valid_address_fields as $field) :
                if (isset($address->$field)
                    && (($field=='address_lines') ? is_array($address->$field) : true )
                ) :
                        $valid_address->$field = $address->$field;
                endif;
            endforeach;

            $person->postal_addresses[] = $valid_address;
        endif;

        if (isset($person->postal_addresses)
            && is_array($person->postal_addresses)
        ) :
            foreach ($person->postal_addresses as $index => $postal_address) :
                if (is_array($postal_address)) :
                    $person->postal_addresses[$index] = (object) $postal_address;
                endif;
            endforeach;

            $this->setPostalAddress($person->postal_addresses);
        endif;

        $person_as_array = (array) $person;
        foreach ($person_as_array as $key => $value) :
            if (is_string($value)
                && !in_array($key, $this->valid_address_fields)
                && !in_array(
                    $key,
                    array(
                        'email',
                        'status',
                        'family_name',
                        'given_name'
                    )
                )
            ) :
                if (!isset($person->custom_fields)
                    || !is_object($person->custom_fields)
                ) :
                        $person->custom_fields = new stdClass();
                endif;

                $person->custom_fields->$key = $value;
            endif;
        endforeach;

        $this->custom_fields = new stdClass();
        if (isset($person->custom_fields)) {
            $custom_fields = $person->custom_fields;
            if (is_array($custom_fields)) {
                $custom_fields = (object) $custom_fields;
            }
            if (is_object($custom_fields)) {
                $this->custom_fields = $custom_fields;
            }
        }
    }

    /**
     * processActivist
     *
     * @param mixed $activist
     * @param mixed $tags
     *
     * @return void
     **/
    public function processActivist($activist, $tags = null, $comment = null)
    {
        $activist   = $this->normalizeActivist($activist);
        (!$tags)    ? : $this->tagActivist($activist, $tags);
        (!$comment) ? : $this->addComment($activist, $comment);

        return $activist;
    }

    /**
     * normalizeActivist
     *
     * @param mixed $activist
     *
     * @return void
     */
    public function normalizeActivist($activist)
    {
        $activist = is_a($activist, 'Person')
            ? $activist
            : new Person($activist);

        return (object) array('person' => $activist);
    }

    /**
     * tagActivist
     *
     * @param mixed $activist
     * @param mixed $tags
     *
     * @return void
     */
    public function tagActivist($activist, $tags = null)
    {
        $activist->add_tags = (!is_array($tags)) ? : $tags;
    }

    /**
     * addComment
     *
     * @param mixed $activist
     * @param mixed $comment
     *
     * @return void
     */
    public function addComment($activist, $comment = null)
    {
        $activist->comments = (!isset($comment)) ? : $comment;
    }

    /**
     * setEmail
     *
     * @param mixed $email
     *
     * @return void
     */
    public function setEmail($email = null)
    {
        $this->email = (!isset($email)) ? : $email;
    }

    /**
     * setStatus
     *
     * @param mixed $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->email_addresses[0]
            ->status = (!in_array($status, $this->valid_subscription_statuses)) ? : $status;
    }

    /**
     * setFamilyName
     *
     * @param mixed $family_name
     *
     * @return void
     */
    public function setFamilyName($family_name = null)
    {
        $this->family_name = (!isset($family_name)) ? : $family_name;
    }

    /**
     * setGivenName
     *
     * @param mixed $given_name
     *
     * @return void
     */
    public function setGivenName($given_name = null)
    {
        $this->given_name = (!isset($given_name)) ? : $given_name;
    }

    /**
     * setPostalAddress
     *
     * @param mixed $address
     *
     * @return void
     */
    public function setPostalAddress($address = null)
    {
        $address = (!is_array($address)) ? : (object) $address;
        if (!is_object($address)) :
            trigger_error(
                'address must be passed as an associative array or object',
                E_USER_ERROR
            );
        endif;

        $valid_address = new stdClass();

        foreach ($this->valid_address_fields as $field) :
            if (isset($address->$field)
                && (($field=='address_lines') ? is_array($address->$field) : true )
            ) :
                    $valid_address->$field = $address->$field;
            endif;
        endforeach;

        $this->postal_addresses[] = $valid_address;
    }

    /**
     * setCustomField
     *
     * @param mixed $key_or_array
     * @param mixed $value
     *
     * @return void
     */
    public function setCustomField($key_or_array, $value = null)
    {
        if (is_array($key_or_array)) :
            foreach ($key_or_array as $k => $v) :
                $this->custom_fields->$k = $v;
            endforeach;
        elseif (is_string($key_or_array) && $value) :
            $this->custom_fields->$key_or_array = $value;
        endif;
    }
}
