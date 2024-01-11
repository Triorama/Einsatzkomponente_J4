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
class GmapkonfigurationModel extends AdminModel
{
  /**
   * @var		string	The prefix to use with controller messages.
   * @since	1.6
   */
  protected $text_prefix = 'COM_EINSATZKOMPONENTE';

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
    $form = $this->loadForm('com_einsatzkomponente.gmapkonfiguration', 'gmapkonfiguration');
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
    $data = Factory::getApplication()->getUserState('com_einsatzkomponente.edit.gmapkonfiguration.data');

    if (empty($data)) {
      $data = $this->getItem();
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
        $db->setQuery('SELECT MAX(ordering) FROM #__eiko_gmap_config');
        $max = $db->loadResult();
        $table->ordering = $max + 1;
      }
    }
  }
}
