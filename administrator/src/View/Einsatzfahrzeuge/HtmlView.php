<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace EikoNamespace\Component\Einsatzkomponente\Administrator\View\Einsatzfahrzeuge;
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

		EinsatzkomponenteHelper::addSubmenu('einsatzfahrzeuge');

		$this->addToolbar();


		$this->sidebar = Sidebar::render();


		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= EinsatzkomponenteHelper::getActions($state->get('filter.category_id'));
		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZFAHRZEUGE'), 'einsatzfahrzeuge.png');

		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew('einsatzfahrzeug.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit') && isset($this->items[0])) {
			ToolbarHelper::editList('einsatzfahrzeug.edit', 'JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			if (isset($this->items[0]->state)) {
				ToolbarHelper::divider();
				ToolbarHelper::custom('einsatzfahrzeuge.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				ToolbarHelper::custom('einsatzfahrzeuge.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			} else if (isset($this->items[0])) {
				//If this component does not use state then show a direct delete button as we can not trash
				ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete', 'JTOOLBAR_DELETE');
			}
			if (isset($this->items[0]->state)) {
				ToolbarHelper::divider();
				ToolbarHelper::archiveList('einsatzfahrzeuge.archive', 'Fahrzeug a.D.');
			}
			if (isset($this->items[0]->checked_out)) {
				ToolbarHelper::custom('einsatzfahrzeuge.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		//Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state)) {
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
				ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete', 'JTOOLBAR_EMPTY_TRASH');
				ToolbarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				//ToolbarHelper::trash('einsatzfahrzeuge.trash','JTOOLBAR_TRASH');
				ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete', 'JTOOLBAR_DELETE');
				ToolbarHelper::divider();
			}
		}
		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_einsatzkomponente');
		}

		$version = new Version;
		if ($version->isCompatible('3.0')) :
			$options = array();
			$options[] = HTMLHelper::_('select.option', '1', 'JPUBLISHED');
			$options[] = HTMLHelper::_('select.option', '0', 'JUNPUBLISHED');
			$options[] = HTMLHelper::_('select.option', '2', 'Fahrzeug a.D.');
			$options[] = HTMLHelper::_('select.option', '-2', 'JTRASHED');
			$options[] = HTMLHelper::_('select.option', '*', 'JALL');
			Sidebar::addFilter(
				Text::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.state'), true)
			);
		endif;

		$this->extra_sidebar = '';
	}

	protected function getSortFields()
	{
		return array(
			'a.id' => Text::_('JGRID_HEADING_ID'),
			'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'a.name' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_NAME'),
			'a.detail1' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_DETAIL1'),
			'a.detail2' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_DETAIL2'),
			'a.department' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_DEPARTMENT'),
			'a.detail3' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_DETAIL3'),
			'a.link' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_LINK'),
			'a.image' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_IMAGE'),
			'a.state' => Text::_('JSTATUS'),
			'a.created_by' => Text::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE_CREATED_BY'),
		);
	}
}
