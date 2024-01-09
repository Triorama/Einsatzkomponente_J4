<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// No direct access.
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
/**
 * Einsatzkomponente model.
 */
class EinsatzkomponenteModelEinsatzberichtForm extends FormModel
{
  var $_item = null;

  /**
   * Method to auto-populate the model state.
   *
   * Note. Calling getState in this method will result in recursion.
   *
   * @since	1.6
   */

  protected function populateState()
  {
    $app = Factory::getApplication('com_einsatzkomponente');
    // Load state from the request userState on edit or from the passed variable on default
    if (Factory::getApplication()->input->get('layout') == 'edit') {
      $id = Factory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
    } else {
      $id = Factory::getApplication()->input->get('id');
      Factory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', $id);
    }
    if (Factory::getApplication()->input->get('addlink') == '1') {
      $id = '';
      Factory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', '');
    }

    $this->setState('einsatzbericht.id', $id);

    // Load the parameters.
    $params = $app->getParams();
    $params_array = $params->toArray();
    if (isset($params_array['item_id'])) {
      $this->setState('einsatzbericht.id', $params_array['item_id']);
    }
    $this->setState('params', $params);
  }

  /**
   * Method to get an ojbect.
   *
   * @param	integer	The id of the object to get.
   *
   * @return	mixed	Object on success, false on failure.
   */
  public function &getData($id = null)
  {
    if ($this->_item === null) {
      $this->_item = false;
      if (empty($id)) {
        $id = $this->getState('einsatzbericht.id');
      }
      // Get a level row instance.
      $table = $this->getTable();
      // Attempt to load the row.
      if ($table->load($id)) {
        // Check published state.
        if ($published = $this->getState('filter.published')) {
          if ($table->state != $published) {
            return $this->_item;
          }
        }
        // Convert the JTable to a clean JObject.
        $properties = $table->getProperties(1);
        $this->_item = ArrayHelper::toObject($properties, 'JObject');
      } elseif ($error = $table->getError()) {
        $this->setError($error);
      }
    }
    return $this->_item;
  }

  public function getTable(
    $type = 'Einsatzbericht',
    $prefix = 'EinsatzkomponenteTable',
    $config = []
  ) {
    $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
    return Table::getInstance($type, $prefix, $config);
  }

  /**
   * Method to check in an item.
   *
   * @param	integer		The id of the row to check out.
   * @return	boolean		True on success, false on failure.
   * @since	1.6
   */
  public function checkin($id = null)
  {
    // Get the id.
    $id = !empty($id) ? $id : (int) $this->getState('einsatzbericht.id');
    if ($id) {
      // Initialise the table
      $table = $this->getTable();
      // Attempt to check the row in.
      if (method_exists($table, 'checkin')) {
        if (!$table->checkin($id)) {
          $this->setError($table->getError());
          return false;
        }
      }
    }
    return true;
  }
  /**
   * Method to check out an item for editing.
   *
   * @param	integer		The id of the row to check out.
   * @return	boolean		True on success, false on failure.
   * @since	1.6
   */
  public function checkout($id = null)
  {
    // Get the user id.
    $id = !empty($id) ? $id : (int) $this->getState('einsatzbericht.id');
    if ($id) {
      // Initialise the table
      $table = $this->getTable();
      // Get the current user object.
      $user = Factory::getUser();
      // Attempt to check the row out.
      if (method_exists($table, 'checkout')) {
        if (!$table->checkout($user->get('id'), $id)) {
          $this->setError($table->getError());
          return false;
        }
      }
    }
    return true;
  }

  /**
   * Method to get the profile form.
   *
   * The base form is loaded from XML
   *
   * @param	array	$data		An optional array of data for the form to interogate.
   * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
   * @return	JForm	A JForm object on success, false on failure
   * @since	1.6
   */
  public function getForm($data = [], $loadData = true)
  {
    // Get the form.
    $form = $this->loadForm('com_einsatzkomponente.einsatzbericht', 'einsatzberichtform', [
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
    $data = $this->getData();

    //	$data->auswahl_orga = implode(',',$array);
    if ($data->auswahl_orga == ''):
      // Vorbelegung Organisationen
      $params = ComponentHelper::getParams('com_einsatzkomponente');
      $data->auswahl_orga = $params->get('pre_auswahl_orga', '');
    endif;

    $params = ComponentHelper::getParams('com_einsatzkomponente');
    $data->watermark_image = $params->get('watermark_image', '');

    return $data;
  }
  /**
   * Method to save the form data.
   *
   * @param	array		The form data.
   * @return	mixed		The user id on success, false on failure.
   * @since	1.6
   */
  public function save($data)
  {
    $id = !empty($data['id']) ? $data['id'] : (int) $this->getState('einsatzbericht.id');
    $state = !empty($data['state']) ? 1 : 0;
    $user = Factory::getUser();
    if ($id) {
      //Check the user can edit this item
      $authorised =
        $user->authorise('core.edit', 'com_einsatzkomponente.einsatzbericht' . $id) ||
        ($authorised = $user->authorise(
          'core.edit.own',
          'com_einsatzkomponente.einsatzbericht' . $id
        ));
      if (
        $user->authorise('core.edit.state', 'com_einsatzkomponente.einsatzbericht' . $id) !==
          true &&
        $state == 1
      ) {
        //The user cannot edit the state of the item.
        $data['state'] = 0;
        $data['einsatzticker'] = 0;
      }
    } else {
      //Check the user can create new items in this section
      $authorised = $user->authorise('core.create', 'com_einsatzkomponente');
      if (
        $user->authorise('core.edit.state', 'com_einsatzkomponente.einsatzbericht' . $id) !==
          true &&
        $state == 1
      ) {
        //The user cannot edit the state of the item.
        $data['state'] = 0;
        $data['einsatzticker'] = 0;
      }
    }
    if ($authorised !== true) {
      Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
      return false;
    }

    $table = $this->getTable();
    if ($table->save($data) === true) {
      return $id;
    } else {
      return false;
    }
  }

  function delete($data)
  {
    $id = !empty($data['id']) ? $data['id'] : (int) $this->getState('einsatzbericht.id');
    if (
      Factory::getUser()->authorise('core.delete', 'com_einsatzkomponente.einsatzbericht' . $id) !==
      true
    ) {
      Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
      return false;
    }
    $table = $this->getTable();
    if ($table->delete($data['id']) === true) {
      return $id;
    } else {
      return false;
    }

    return true;
  }
}
