<?php

/**
 * helpers.php
 *
 * countdown
 */

function _countdown_parse_bool(string $_value) : bool {
  $value = strtolower($_value);

  switch ($value) {
  case 'off':
  case 'false':
  case 'no':
    return false;
  }

  return $value;
}
