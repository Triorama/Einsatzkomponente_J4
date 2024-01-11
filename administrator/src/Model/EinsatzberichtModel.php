<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace EikoNamespace\Component\Einsatzkomponente\Administrator\Model;
// No direct access.
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Eikonamespace\Component\Einsatzkomponente\Administrator\Field;
/**
 * Einsatzkomponente model.
 */
class EinsatzberichtModel extends AdminModel
{
  /**
   * The type alias for this content type.
   *
   * @var    string
   * @since  3.2
   */
  public $typeAlias = 'com_einsatzkomponente.einsatzbericht';
  /**
   * @var		string	The prefix to use with controller messages.
   * @since	1.6
   */
  protected $text_prefix = 'COM_EINSATZKOMPONENTE';
  /**
   * Name of the form
   *
   * @var string
   * @since  4.0.0
   */
  protected $formName = 'einsatzbericht';
  /**
   * Method to get the record form.
   *
   * @param	array	$data		An optional array of data for the form to interogate.
   * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
   * @return	JForm	A JForm object on success, false on failure
   * @since	1.6
   */
  public function getForm($data = [], $loadData = true)
  {
    // Initialise variables.
    $app = Factory::getApplication();
    // Get the form.
    $form = $this->loadForm($this->typeAlias, 'einsatzbericht', ['control' => 'jform', 'load_data' => $loadData]);
    if (empty($form)) {
      return false;
    }
    return $form;
  }

  /**
   * Method to get the data that should be injected in the form.
   *
   * @return	mixed	The data for the form.
   * @since	1.6
   */
  protected function loadFormData()
  {
    // Check the session for previously entered form data.
    $data = Factory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzbericht.data', []);
    if (empty($data)) {
      $data = $this->getItem();

      //Support for multiple or not foreign key field: auswahl_orga
      $array = [];
      foreach ((array) $data->auswahl_orga as $value):
        if (!is_array($value)):
          $array[] = $value;
        endif;
      endforeach;
      $data->auswahl_orga = implode(',', $array);
      // if ($data->auswahl_orga == ''):
      //   //Vorbelegung Organisationen
      //   $db = Factory::getDbo();
      //   $db->setQuery('SELECT id FROM #__eiko_organisationen WHERE ffw="1" LIMIT 1');
      //   $standard = $db->loadResult();
      //   $data->auswahl_orga = $standard['id'];
      //   $params = ComponentHelper::getParams('com_einsatzkomponente');
      //   $data->auswahl_orga = $params->get('pre_auswahl_orga', '');
      // endif;

      //Support for multiple or not foreign key field: vehicles
      $array = [];
      foreach ((array) $data->vehicles as $value):
        if (!is_array($value)):
          $array[] = $value;
        endif;
      endforeach;
      $data->vehicles = implode(',', $array);

      //Support for multiple or not foreign key field: ausruestung
      $array = [];
      foreach ((array) $data->ausruestung as $value):
        if (!is_array($value)):
          $array[] = $value;
        endif;
      endforeach;
      $data->ausruestung = implode(',', $array);

      //   //Support for multiple or not foreign key field: vehicles
      //   $array = [];
      //   foreach ((array) $data->params as $value):
      //     if (!is_array($value)):
      //       $array[] = $value;
      //     endif;
      //   endforeach;
      //   $data->params = implode(',', $array);
    }

    $params = ComponentHelper::getParams('com_einsatzkomponente');

    if (is_object($data)) {
      $data->watermark_image = $params->get('watermark_image', '');
    }

    return $data;
  }
  /**
   * Method to validate the form data.
   *
   * @param   Form    $form   The form to validate against.
   * @param   array   $data   The data to validate.
   * @param   string  $group  The name of the field group to validate.
   *
   * @return  array|boolean  Array of filtered data if valid, false otherwise.
   *
   * @see     \Joomla\CMS\Form\FormRule
   * @see     JFilterInput
   * @since   3.7.0
   */
  public function validate($form, $data, $group = null)
  {
    if (!$this->getCurrentUser()->authorise('core.admin', 'com_einsatzkomponente')) {
      if (isset($data['rules'])) {
        unset($data['rules']);
      }
    }

    return parent::validate($form, $data, $group);
  }

  /**
   * Method to get a single record.
   *
   * @param	integer	The id of the primary key.
   *
   * @return	mixed	Object on success, false on failure.
   * @since	1.6
   */
  public function getItem($pk = null)
  {
    if ($item = parent::getItem($pk)) {
      //Do any procesing on fields here if needed
    }

    return $item;
  }
  /**
   * Prepare and sanitise the table prior to saving.
   *
   * @since	1.6
   */
  protected function prepareTable($table)
  {
    jimport('joomla.filter.output');
    if (empty($table->id)) {
      // Set ordering to the last item if not set
      if (@$table->ordering === '') {
        $db = Factory::getDbo();
        $db->setQuery('SELECT MAX(ordering) FROM #__eiko_einsatzberichte');
        $max = $db->loadResult();
        $table->ordering = $max + 1;
      }
    }
  }

  /**
   * Method to delete rows.
   *
   * @param   array  &$pks  An array of item ids.
   *
   * @return  boolean  Returns true on success, false on failure.
   *
   * @since   1.6
   */
  public function delete(&$pks)
  {
    $db = Factory::getDBO();
    foreach ($pks as $id) {
      $db->setQuery('DELETE FROM #__eiko_einsatzberichte WHERE id=' . $id);
      $db->execute();
    }
    return true;
  }
}
