<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
defined('JPATH_BASE') or die();
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class FormFieldCreatedby extends FormField
{
  /**
   * The form field type.
   *
   * @var		string
   * @since	1.6
   */
  protected $type = 'createdby';

  /**
   * Method to get the field input markup.
   *
   * @return	string	The field input markup.
   * @since	1.6
   */
  protected function getInput()
  {
    // Initialize variables.
    $html = [];

    //Load user
    $user_id = $this->value;
    if ($user_id) {
      $user = Factory::getUser($user_id);
    } else {
      $user = Factory::getUser();
      $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $user->id . '" />';
    }
    $html[] = '<div>' . $user->name . ' (' . $user->username . ')</div>';

    return implode($html);
  }
}
