<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
namespace EikoNamespace\Component\Einsatzkomponente\Administrator\Model;
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

/**
 * Methods supporting a list of Einsatzkomponente records.
 */
class KategorienModel extends ListModel
{
  /**
   * Constructor.
   *
   * @param    array    An optional associative array of configuration settings.
   * @see        JController
   * @since    1.6
   */
  public function __construct($config = [])
  {
    if (empty($config['filter_fields'])) {
      $config['filter_fields'] = [
        'id',
        'a.id',
        'ordering',
        'a.ordering',
        'title',
        'a.title',
        'image',
        'a.image',
        'state',
        'a.state',
        'created_by',
        'a.created_by',
        'params',
        'a.params',
      ];
    }
    parent::__construct($config);
  }
  /**
   * Method to auto-populate the model state.
   *
   * Note. Calling getState in this method will result in recursion.
   */
  protected function populateState($ordering = null, $direction = null)
  {
    // Initialise variables.
    $app = Factory::getApplication('administrator');
    // Load the filter state.
    $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
    $this->setState('filter.search', $search);
    $published = $app->getUserStateFromRequest(
      $this->context . '.filter.state',
      'filter_published',
      '',
      'string'
    );
    $this->setState('filter.state', $published);
    // Load the parameters.
    $params = ComponentHelper::getParams('com_einsatzkomponente');
    $this->setState('params', $params);
    // List state information.
    parent::populateState('a.title', 'asc');
  }
  /**
   * Method to get a store id based on model configuration state.
   *
   * This is necessary because the model is used by the component and
   * different modules that might need different sets of data or different
   * ordering requirements.
   *
   * @param	string		$id	A prefix for the store id.
   * @return	string		A store id.
   * @since	1.6
   */
  protected function getStoreId($id = '')
  {
    // Compile the store id.
    $id .= ':' . $this->getState('filter.search');
    $id .= ':' . $this->getState('filter.state');
    return parent::getStoreId($id);
  }
  /**
   * Build an SQL query to load the list data.
   *
   * @return	JDatabaseQuery
   * @since	1.6
   */
  protected function getListQuery()
  {
    // Create a new query object.
    $db = $this->getDbo();
    $query = $db->getQuery(true);
    // Select the required fields from the table.
    $query->select($this->getState('list.select', 'a.*'));
    $query->from('#__eiko_tickerkat AS a');
    // Join over the user field 'created_by'
    $query->select('created_by.name AS created_by');
    $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
      $query->where('a.state = ' . (int) $published);
    } elseif ($published === '') {
      $query->where('(a.state IN (0, 1))');
    }
    // Filter by search in title
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      if (stripos($search, 'id:') === 0) {
        $query->where('a.id = ' . (int) substr($search, 3));
      } else {
        $search = $db->Quote('%' . $db->escape($search, true) . '%');
        $query->where('( a.title LIKE ' . $search . ' )');
      }
    }
    $orderCol = $this->state->get('list.ordering');
    $orderDirn = $this->state->get('list.direction');
    if ($orderCol && $orderDirn) {
      $query->order($db->escape($orderCol . ' ' . $orderDirn));
    }
    return $query;
  }
}
