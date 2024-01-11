<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace EikoNamespace\Component\Einsatzkomponente\Administrator\Field;

defined('JPATH_BASE') or die();

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;

/**
 * Supports an HTML select list of categories
 */
class OptgroupField extends FormField
{
  /**
   * The form field type.
   *
   * @var         string
   * @since       1.6
   */
  protected $type = 'Optgroup';
  /**
   * Method to get the field input markup.
   *
   * @return      string  The field input markup.
   * @since       1.6
   */
  protected function getInput()
  {
    $selected = '';

    // Initialize variables.
    $html = [];
    $db = Factory::getContainer()->get(DatabaseInterface::class);
    $query = 'SELECT id,name from #__eiko_organisationen WHERE state=1 ORDER BY ordering ASC';
    $db->setQuery($query);
    $orgs = $db->loadObjectList();
    $html[] .= '<select id="' . $this->id . '" name="' . $this->name . '[]" multiple>';
    $html[] .= '<option>&nbsp;</option>';
    foreach ($orgs as $org) {
      $html[] .= '<optgroup label="' . $org->name . '">';
      $query = 'SELECT id,name from #__eiko_fahrzeuge where department = "' . $org->id . '" and state = 1 order by ordering ASC';
      $db->setQuery($query);
      $vehicles = $db->loadObjectList();

      if (count($vehicles) > 1) {
        $v = [];
        foreach ($vehicles as $vehicle) {
          $v[] .= $vehicle->id;
        }
        $html[] .= '<option value="' . implode(',', $v) . '">' . $org->name . ' ( alle Fahrzeuge)</option>';
      }

      foreach ($vehicles as $vehicle) {
        if (is_array($this->value)):
          foreach ($this->value as $value) {
            if ($value == $vehicle->id):
              $selected = 'selected';
            endif;
          }
        endif;
        $html[] .= '<option ' . $selected . ' value="' . $vehicle->id . '">' . $vehicle->name . ' ( ' . $org->name . ' ) </option>';
        $selected = '';
      }
      $html[] .= '</optgroup>';
    }

    $query = 'SELECT id,name from #__eiko_fahrzeuge WHERE department = "" AND state = 1 ORDER BY ordering ASC';
    $db->setQuery($query);
    if ($vehicles = $db->loadObjectList()):
      $html[] .= '<optgroup label="sonstige">';
      foreach ($vehicles as $vehicle) {
        if (is_array($this->value)):
          foreach ($this->value as $value) {
            if ($value == $vehicle->id):
              $selected = 'selected';
            endif;
          }
        endif;
        $html[] .= '<option ' . $selected . ' value="' . $vehicle->id . '">' . $vehicle->name . ' ( sonstige ) </option>';
        $selected = '';
      }
      $html[] .= '</optgroup>';
    endif;

    $query = 'SELECT id,name from #__eiko_fahrzeuge where state = 2 order by ordering ASC';
    $db->setQuery($query);
    if ($vehicles = $db->loadObjectList()):
      $html[] .= '<optgroup label="außer Dienst">';
      foreach ($vehicles as $vehicle) {
        if (is_array($this->value)):
          foreach ($this->value as $value) {
            if ($value == $vehicle->id):
              $selected = 'selected';
            endif;
          }
        endif;
        $html[] .= '<option ' . $selected . ' value="' . $vehicle->id . '">' . $vehicle->name . ' - a.D. ( ID ' . $vehicle->id . ' ) </option>';
        $selected = '';
      }
      $html[] .= '</optgroup>';
    endif;

    $html[] .= '</select>';
    return implode($html);
  }

  protected function getLabel()
  {
    return str_replace($this->id, $this->id . '_name', parent::getLabel());
  }
}
