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

/**
 * Einsatzkomponente model.
 */
class EinsatzfahrzeugModel extends AdminModel
{
  /**
   * @var		string	The prefix to use with controller messages.
   * @since	1.6
   */
  protected $text_prefix = 'COM_EINSATZKOMPONENTE';
  /**
   * Returns a reference to the a Table object, always creating it.
   *
   * @param	type	The table type to instantiate
   * @param	string	A prefix for the table class name. Optional.
   * @param	array	Configuration array for model. Optional.
   * @return	JTable	A database object
   * @since	1.6
   */

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
    $form = $this->loadForm('com_einsatzkomponente.einsatzfahrzeug', 'einsatzfahrzeug', [
      'control' => 'jform',
      'load_data' => $loadData,
    ]);
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
    $data = Factory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzfahrzeug.data', []);
    if (empty($data)) {
      $data = $this->getItem();

      //Support for multiple or not foreign key field: ausruestung
      $array = [];
      foreach ((array) $data->ausruestung as $value):
        if (!is_array($value)):
          $array[] = $value;
        endif;
      endforeach;
      $data->ausruestung = implode(',', $array);

      //Support for multiple or not foreign key field: vehicles
      $array = [];
      foreach ((array) $data->params as $value):
        if (!is_array($value)):
          $array[] = $value;
        endif;
      endforeach;
      $data->params = implode(',', $array);
    }
    return $data;
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
        $db->setQuery('SELECT MAX(ordering) FROM__eiko_fahrzeuge');
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
      $db->setQuery('DELETE FROM #__eiko_fahrzeuge WHERE id=' . $id);
      try {
        $db->execute();
      } catch (RuntimeException $e) {
        throw new Exception($e->getMessage(), 500);
      }
    }
    return true;
  }
}
