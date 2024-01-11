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
use Joomla\CMS\Language\Text;

/**
 * Methods supporting a list of Einsatzkomponente records.
 */
class EinsatzberichteModel extends ListModel
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
        'alarmierungsart',
        'a.alarmierungsart',
        'einsatzkategorie',
        'a.einsatzkategorie',
        'einsatzart',
        'a.einsatzart',
        'image',
        'a.image',
        'images',
        'a.images',
        'alarmierungszeit',
        'a.alarmierungszeit',
        'ausfahrtszeit',
        'a.ausfahrtszeit',
        'einsatzende',
        'a.einsatzende',
        'address',
        'a.address',
        'summary',
        'a.summary',
        'auswahl_orga',
        'a.auswahl_orga',
        'vehicles',
        'a.vehicles',
        'ausruestung',
        'a.ausruestung',
        'einsatzleiter',
        'a.einsatzleiter',
        'einsatzfuehrer',
        'a.einsatzfuehrer',
        'people',
        'a.people',
        'desc',
        'a.desc',
        'gmap_report_latitude',
        'a.gmap_report_latitude',
        'gmap_report_longitude',
        'a.gmap_report_longitude',
        'gmap',
        'a.gmap',
        'status_fb',
        'a.status_fb',
        'presse_label',
        'a.presse_label',
        'presse',
        'a.presse',
        'presse2_label',
        'a.presse2_label',
        'presse2',
        'a.presse2',
        'presse3_label',
        'a.presse3_label',
        'presse3',
        'a.presse3',
        'updatedate',
        'a.updatedate',
        'createdate',
        'a.createdate',
        'einsatzticker',
        'a.einsatzticker',
        'department',
        'a.department',
        'notrufticker',
        'a.notrufticker',
        'status',
        'a.status',
        'article_id',
        'a.article_id',
        'counter',
        'a.counter',
        'state',
        'a.state',
        'created_by',
        'a.created_by',
        'modified_by',
        'a.modified_by',
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
    $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
    $this->setState('filter.search', $search);

    $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
    $this->setState('filter.state', $published);

    //Filtering alarmierungsart
    $this->setState('filter.alarmierungsart', $this->getUserStateFromRequest($this->context . '.filter.alarmierungsart', 'filter_alarmierungsart', '', 'string'));

    //Filtering einsatzkategorie
    $this->setState('filter.einsatzkategorie', $this->getUserStateFromRequest($this->context . '.filter.einsatzkategorie', 'filter_einsatzkategorie', '', 'string'));

    //Filtering einsatzart
    $this->setState('filter.einsatzart', $this->getUserStateFromRequest($this->context . '.filter.einsatzart', 'filter_einsatzart', '', 'string'));

    //Filtering alarmierungszeit
    $this->setState('filter.alarmierungszeit.from', $this->getUserStateFromRequest($this->context . '.filter.alarmierungszeit.from', 'filter_from_alarmierungszeit', '', 'string'));
    $this->setState('filter.alarmierungszeit.to', $this->getUserStateFromRequest($this->context . '.filter.alarmierungszeit.to', 'filter_to_alarmierungszeit', '', 'string'));

    //Filtering auswahl_orga
    $this->setState('filter.auswahl_orga', $this->getUserStateFromRequest($this->context . '.filter.auswahl_orga', 'filter_auswahl_orga', '', 'string'));

    //Filtering ausruestung
    $this->setState('filter.ausruestung', $this->getUserStateFromRequest($this->context . '.filter.ausruestung', 'filter_ausruestung', '', 'string'));

    //Filtering created_by
    $this->setState('filter.created_by', $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '', 'string'));

    //Filtering modified_by
    $this->setState('filter.modified_by', $this->getUserStateFromRequest($this->context . '.filter.modified_by', 'filter_modified_by', '', 'string'));

    // Load the parameters.
    $params = ComponentHelper::getParams('com_einsatzkomponente');
    $this->setState('params', $params);

    // List state information.
    parent::populateState('a.alarmierungszeit', 'desc');
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
    $query->select($this->getState('list.select', 'DISTINCT a.*'));
    $query->from('#__eiko_einsatzberichte AS a');

    // Join over the users for the checked out user
    //$query->select("uc.name AS editor");
    //$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
    // Join over the foreign key 'alarmierungsart'
    $query->select('#__eiko_alarmierungsarten_1662662.title AS alarmierungsarten_title_1662662');
    $query->join('LEFT', '#__eiko_alarmierungsarten AS #__eiko_alarmierungsarten_1662662 ON #__eiko_alarmierungsarten_1662662.id = a.alarmierungsart');
    // Join over the foreign key 'einsatzkategorie'
    $query->select('#__eiko_einsatzkategorie_1662677.title AS einsatzkategorien_title_1662677');
    $query->join('LEFT', '#__eiko_einsatzkategorie AS #__eiko_einsatzkategorie_1662677 ON #__eiko_einsatzkategorie_1662677.id = a.einsatzkategorie');
    // Join over the foreign key 'einsatzart'
    $query->select('#__eiko_einsatzarten_1662650.title AS einsatzarten_title_1662650');
    $query->join('LEFT', '#__eiko_einsatzarten AS #__eiko_einsatzarten_1662650 ON #__eiko_einsatzarten_1662650.id = a.einsatzart');
    // Join over the foreign key 'auswahl_orga'
    $query->select('#__eiko_organisationen_1662678.name AS organisationen_name_1662678');
    $query->join('LEFT', '#__eiko_organisationen AS #__eiko_organisationen_1662678 ON #__eiko_organisationen_1662678.id = a.auswahl_orga');
    // Join over the foreign key 'auswahl_orga'
    $query->select('#__eiko_ausruestung_1662678.name AS ausruestung_name_1662678');
    $query->join('LEFT', '#__eiko_ausruestung AS #__eiko_ausruestung_1662678 ON #__eiko_ausruestung_1662678.id = a.ausruestung');
    // Join over the user field 'created_by'
    $query->select('created_by.name AS created_by');
    $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
    // Join over the user field 'modified_by'
    $query->select('modified_by.name AS modified_by');
    $query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
      $query->where('a.state = ' . (int) $published);
    } elseif ($published === '') {
      $query->where('(a.state IN (0, 1,2))');
    }

    // Filter by search in title
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      if (stripos($search, 'id:') === 0) {
        $query->where('a.id = ' . (int) substr($search, 3));
      } else {
        $search = $db->Quote('%' . $db->escape($search, true) . '%');
        $query->where(
          '( a.alarmierungsart LIKE ' .
            $search .
            '  OR  a.einsatzkategorie LIKE ' .
            $search .
            ' OR  a.einsatzart LIKE ' .
            $search .
            ' OR  a.address LIKE ' .
            $search .
            '  OR  a.summary LIKE ' .
            $search .
            '  OR  a.auswahl_orga LIKE ' .
            $search .
            '  OR  a.vehicles LIKE ' .
            $search .
            '  OR  a.ausruestung LIKE ' .
            $search .
            '  OR  a.einsatzleiter LIKE ' .
            $search .
            '  OR a.einsatzfuehrer LIKE ' .
            $search .
            '  OR a.desc LIKE ' .
            $search .
            ' )'
        );
      }
    }

    //Filtering alarmierungsart
    $filter_alarmierungsart = $this->state->get('filter.alarmierungsart');
    if ($filter_alarmierungsart) {
      $query->where("a.alarmierungsart = '" . $db->escape($filter_alarmierungsart) . "'");
    }

    //Filtering einsatzkategorie
    $filter_einsatzkategorie = $this->state->get('filter.einsatzkategorie');
    if ($filter_einsatzkategorie) {
      $query->where("a.einsatzkategorie = '" . $db->escape($filter_einsatzkategorie) . "'");
    }

    //Filtering einsatzart
    $filter_einsatzart = $this->state->get('filter.einsatzart');
    if ($filter_einsatzart) {
      $query->where("a.einsatzart = '" . $db->escape($filter_einsatzart) . "'");
    }

    //Filtering alarmierungszeit
    $filter_alarmierungszeit_from = $this->state->get('filter.alarmierungszeit.from');
    if ($filter_alarmierungszeit_from) {
      $query->where("a.alarmierungszeit >= '" . $db->escape($filter_alarmierungszeit_from) . "'");
    }
    $filter_alarmierungszeit_to = $this->state->get('filter.alarmierungszeit.to');
    if ($filter_alarmierungszeit_to) {
      $query->where("a.alarmierungszeit <= '" . $db->escape($filter_alarmierungszeit_to) . "'");
    }

    //Filtering auswahl_orga
    $filter_auswahl_orga = $this->state->get('filter.auswahl_orga');
    if ($filter_auswahl_orga) {
      $query->where('FIND_IN_SET(' . $filter_auswahl_orga . ',a.auswahl_orga)');
    }

    //Filtering vehicles
    $filter_vehicles = $this->state->get('filter.vehicles');
    if ($filter_vehicles) {
      $query->where('FIND_IN_SET(' . $filter_vehicles . ',a.vehicles)');
    }

    //Filtering ausruestung
    $filter_ausruestung = $this->state->get('filter.ausruestung');
    if ($filter_ausruestung) {
      $query->where('FIND_IN_SET(' . $filter_ausruestung . ',a.ausruestung)');
    }

    //Filtering created_by
    $filter_created_by = $this->state->get('filter.created_by');
    if ($filter_created_by) {
      $query->where("a.created_by = '" . $db->escape($filter_created_by) . "'");
    }

    //Filtering modified_by
    $filter_modified_by = $this->state->get('filter.modified_by');
    if ($filter_modified_by) {
      $query->where("a.modified_by = '" . $db->escape($filter_modified_by) . "'");
    }

    // Add the list ordering clause.
    $orderCol = $this->state->get('list.ordering');
    $orderDirn = $this->state->get('list.direction');
    if ($orderCol && $orderDirn) {
      $query->order($db->escape($orderCol . ' ' . $orderDirn));
    }

    return $query;
  }

  public function getItems()
  {
    $items = parent::getItems();

    foreach ($items as $oneItem) {
      if (isset($oneItem->alarmierungsart)) {
        $values = explode(',', $oneItem->alarmierungsart);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('title')
            ->from('#__eiko_alarmierungsarten')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->title;
          }
        }

        $oneItem->alarmierungsart = !empty($textValue) ? implode(', ', $textValue) : $oneItem->alarmierungsart;
      }

      if (isset($oneItem->einsatzkategorie)) {
        $values = explode(',', $oneItem->einsatzkategorie);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('title')
            ->from('#__eiko_einsatzkategorie')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->title;
          }
        }

        $oneItem->einsatzkategorie = !empty($textValue) ? implode(', ', $textValue) : $oneItem->einsatzkategorie;
      }

      if (isset($oneItem->einsatzart)) {
        $values = explode(',', $oneItem->einsatzart);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('id,title')
            ->from('#__eiko_einsatzarten')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $oneItem->einsatzart_id = $results->id;
            $textValue[] = $results->title;
          }
        }

        $oneItem->einsatzart = !empty($textValue) ? implode(', ', $textValue) : $oneItem->einsatzart;
      }

      if (isset($oneItem->images)) {
        $values = explode(',', $oneItem->images);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('image')
            ->from('#__eiko_images')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->image;
          }
        }

        $oneItem->images = !empty($textValue) ? implode(', ', $textValue) : $oneItem->images;
      }

      if (isset($oneItem->auswahl_orga)) {
        $values = explode(',', $oneItem->auswahl_orga);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_organisationen')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = '<span class="badge bg-warning text-dark">' . $results->name . '</span>';
          }
        }
        //$textValue = array_reverse($textValue);
        $oneItem->auswahl_orga = !empty($textValue) ? implode('<br/>', $textValue) : $oneItem->auswahl_orga;
      }

      if (isset($oneItem->vehicles)) {
        $values = explode(',', $oneItem->vehicles);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_fahrzeuge')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->name;
          }
        }

        $oneItem->vehicles = !empty($textValue) ? implode(', ', $textValue) : $oneItem->vehicles;
      }

      if (isset($oneItem->ausruestung)) {
        $values = explode(',', $oneItem->ausruestung);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_ausruestung')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->name;
          }
        }

        $oneItem->ausruestung = !empty($textValue) ? implode(', ', $textValue) : $oneItem->ausruestung;
      }

      if (isset($oneItem->einsatzleiter_ftm)) {
        $values = explode(',', $oneItem->einsatzleiter_ftm);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_fahrzeuge')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->name;
          }
        }

        $oneItem->einsatzleiter_ftm = !empty($textValue) ? implode(', ', $textValue) : $oneItem->einsatzleiter_ftm;
      }

      if (isset($oneItem->einsatzfuehrer_ftm)) {
        $values = explode(',', $oneItem->einsatzfuehrer_ftm);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_fahrzeuge')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->name;
          }
        }

        $oneItem->einsatzfuehrer_ftm = !empty($textValue) ? implode(', ', $textValue) : $oneItem->einsatzfuehrer_ftm;
      }

      if (isset($oneItem->people_ftm)) {
        $values = explode(',', $oneItem->people_ftm);

        $textValue = [];
        foreach ($values as $value) {
          $db = Factory::getDbo();
          $query = $db->getQuery(true);
          $query
            ->select('name')
            ->from('#__eiko_fahrzeuge')
            ->where('id = ' . $db->quote($db->escape($value)));
          $db->setQuery($query);
          $results = $db->loadObject();
          if ($results) {
            $textValue[] = $results->name;
          }
        }

        $oneItem->people_ftm = !empty($textValue) ? implode(', ', $textValue) : $oneItem->people_ftm;
      }
      $oneItem->status_fb = Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_STATUS_FB_OPTION_' . strtoupper($oneItem->status_fb));
      $oneItem->status = Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_STATUS_OPTION_' . strtoupper($oneItem->status));

      // if (isset($oneItem->article_id)) {
      // $values = explode(',', $oneItem->article_id);

      // $textValue = array();
      // foreach ($values as $value){
      // $db = JFactory::getDbo();
      // $query = $db->getQuery(true);
      // $query
      // ->select('title')
      // ->from('#__content')
      // ->where('id = ' . $db->quote($db->escape($value)));
      // $db->setQuery($query);
      // $results = $db->loadObject();
      // if ($results) {
      // $textValue[] = $results->title;
      // }
      // }

      // $oneItem->article_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->article_id;

      // }
    }
    return $items;
  }
  public function getForm($data = [], $loadData = true)
  {
    $form = $this->loadForm('com_einsatzkomponente.einsatbericht', 'einsatzbericht');

    if (empty($form)) {
      return false;
    }

    return $form;
  }
}
