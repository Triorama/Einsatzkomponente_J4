<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
namespace EikoNamespace\Component\Einsatzkomponente\Administrator\View\Alarmierungsarten;
// No direct access
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Version;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\Helpers\ListHelper;
use EikoNamespace\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView
{
  protected $items;
  protected $pagination;
  protected $state;
  /**
   * Display the view
   */
  public function display($tpl = null)
  {
    $this->state = $this->get('State');
    $this->items = $this->get('Items');
    $this->pagination = $this->get('Pagination');
    // Check for errors.
    if (count($errors = $this->get('Errors'))) {
      throw new \Exception(implode("\n", $errors));
    }

    EinsatzkomponenteHelper::addSubmenu('alarmierungsarten');

    $this->addToolbar();

    $version = new Version();
    if ($version->isCompatible('3.0')):
      $this->sidebar = Sidebar::render();
    endif;

    parent::display($tpl);
  }
  /**
   * Add the page title and toolbar.
   *
   * @since	1.6
   */
  protected function addToolbar()
  {
    $state = $this->get('State');
    $canDo = EinsatzkomponenteHelper::getActions($state->get('filter.category_id'));
    ToolBarHelper::title(
      Text::_('COM_EINSATZKOMPONENTE_TITLE_ALARMIERUNGSARTEN'),
      'alarmierungsarten.png'
    );
    //Check if the form exists before showing the add/edit buttons

    if ($canDo->get('core.create')) {
      ToolBarHelper::addNew('alarmierungsart.add', 'JTOOLBAR_NEW');
    }
    if ($canDo->get('core.edit') && isset($this->items[0])) {
      ToolBarHelper::editList('alarmierungsart.edit', 'JTOOLBAR_EDIT');
    }

    if ($canDo->get('core.edit.state')) {
      if (isset($this->items[0]->state)) {
        ToolBarHelper::divider();
        ToolBarHelper::custom(
          'alarmierungsarten.publish',
          'publish.png',
          'publish_f2.png',
          'JTOOLBAR_PUBLISH',
          true
        );
        ToolBarHelper::custom(
          'alarmierungsarten.unpublish',
          'unpublish.png',
          'unpublish_f2.png',
          'JTOOLBAR_UNPUBLISH',
          true
        );
      } elseif (isset($this->items[0])) {
        //If this component does not use state then show a direct delete button as we can not trash
        ToolBarHelper::deleteList('', 'alarmierungsarten.delete', 'JTOOLBAR_DELETE');
      }
      //            if (isset($this->items[0]->state)) {
      //			    ToolbarHelper::divider();
      //			    ToolbarHelper::archiveList('alarmierungsarten.archive','JTOOLBAR_ARCHIVE');
      //            }
      if (isset($this->items[0]->checked_out)) {
        ToolBarHelper::custom(
          'alarmierungsarten.checkin',
          'checkin.png',
          'checkin_f2.png',
          'JTOOLBAR_CHECKIN',
          true
        );
      }
    }

    //Show trash and delete for components that uses the state field
    if (isset($this->items[0]->state)) {
      if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
        ToolBarHelper::deleteList('', 'alarmierungsarten.delete', 'JTOOLBAR_EMPTY_TRASH');
        ToolBarHelper::divider();
      } elseif ($canDo->get('core.edit.state')) {
        //ToolbarHelper::trash('alarmierungsarten.trash','JTOOLBAR_TRASH');
        ToolBarHelper::deleteList('', 'alarmierungsarten.delete', 'JTOOLBAR_DELETE');
        ToolBarHelper::divider();
      }
    }
    if ($canDo->get('core.admin')) {
      ToolBarHelper::preferences('com_einsatzkomponente');
    }

    $version = new Version();
    if ($version->isCompatible('3.0')):
      //Set sidebar action - New in 3.0
      Sidebar::setAction('index.php?option=com_einsatzkomponente&view=alarmierungsarten');
      $options = [];
      $options[] = HTMLHelper::_('select.option', '1', 'JPUBLISHED');
      $options[] = HTMLHelper::_('select.option', '0', 'JUNPUBLISHED');
      $options[] = HTMLHelper::_('select.option', '*', 'JALL');
      Sidebar::addFilter(
        Text::_('JOPTION_SELECT_PUBLISHED'),
        'filter_published',
        HTMLHelper::_(
          'select.options',
          $options,
          'value',
          'text',
          $this->state->get('filter.state'),
          true
        )
      );
    endif;

    $this->extra_sidebar = '';
  }

  protected function getSortFields()
  {
    return [
      'a.id' => Text::_('JGRID_HEADING_ID'),
      'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
      'a.title' => Text::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSARTEN_TITLE'),
      'a.image' => Text::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSARTEN_IMAGE'),
      'a.state' => Text::_('JSTATUS'),
      'a.created_by' => Text::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSARTEN_CREATED_BY'),
    ];
  }
}
