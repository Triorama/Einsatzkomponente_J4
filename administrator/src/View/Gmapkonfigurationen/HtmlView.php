<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
namespace EikoNamespace\Component\Einsatzkomponente\Administrator\View\Gmapkonfigurationen;
// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Version;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
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
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors));
		}
        
		EinsatzkomponenteHelper::addSubmenu('gmapkonfigurationen');
        
		$this->addToolbar();
        
		$version = new Version;
        if ($version->isCompatible('3.0')) :
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
		require_once JPATH_COMPONENT.'/helpers/einsatzkomponente.php';

		$state	= $this->get('State');
		$canDo	= EinsatzkomponenteHelper::getActions($state->get('filter.category_id'));

		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_GMAPKONFIGURATIONEN'), 'gmapkonfigurationen.png');


           if ($canDo->get('core.create')) {
			    ToolbarHelper::addNew('gmapkonfiguration.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    ToolbarHelper::editList('gmapkonfiguration.edit','JTOOLBAR_EDIT');
		    }

        

//		if ($canDo->get('core.edit.state')) {
//
//            if (isset($this->items[0]->state)) {
//			    ToolbarHelper::divider();
//			    ToolbarHelper::custom('gmapkonfigurationen.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
//			    ToolbarHelper::custom('gmapkonfigurationen.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
//            } else if (isset($this->items[0])) {
//                //If this component does not use state then show a direct delete button as we can not trash
//                ToolbarHelper::deleteList('', 'gmapkonfigurationen.delete','JTOOLBAR_DELETE');
//            }
//
//            if (isset($this->items[0]->state)) {
//			    ToolbarHelper::divider();
//			    ToolbarHelper::archiveList('gmapkonfigurationen.archive','JTOOLBAR_ARCHIVE');
//            }
//            if (isset($this->items[0]->checked_out)) {
//            	ToolbarHelper::custom('gmapkonfigurationen.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
//            }
//		}
        
        //Show trash and delete for components that uses the state field
//        if (isset($this->items[0]->state)) {
//		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
//			    ToolbarHelper::deleteList('', 'gmapkonfigurationen.delete','JTOOLBAR_EMPTY_TRASH');
//			    ToolbarHelper::divider();
//		    } else if ($canDo->get('core.edit.state')) {
//			    ToolbarHelper::trash('gmapkonfigurationen.trash','JTOOLBAR_TRASH');
//			    ToolbarHelper::divider();
//		    }
//        }

//		if ($canDo->get('core.admin')) {
//			ToolbarHelper::preferences('com_einsatzkomponente');
//		}
        
        //Set sidebar action - New in 3.0
//		Sidebar::setAction('index.php?option=com_einsatzkomponente&view=gmapkonfigurationen');
//        
//        $this->extra_sidebar = '';
//        
//		Sidebar::addFilter(
//
//			JText::_('JOPTION_SELECT_PUBLISHED'),
//
//			'filter_published',
//
//			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)
//
//		);

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => Text::_('JGRID_HEADING_ID'),
		'a.state' => Text::_('JSTATUS'),
		'a.created_by' => Text::_('COM_EINSATZKOMPONENTE_GMAPKONFIGURATIONEN_CREATED_BY'),
		);
	}

    
}
