<?php

/**
 * @see https://tinkerwell.app/shares/kellymears/ddba3adc-b8cb-4ac0-b9d6-9b62f45cebfe
 */

$people
  ->request()
  ->filter(function ($person) {
    return $person->name->first == 'Kelly';
  })
  ->first()
  ->getEmail();
