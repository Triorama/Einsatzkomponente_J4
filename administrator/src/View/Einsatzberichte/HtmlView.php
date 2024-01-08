<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace EikoNamespace\Component\Einsatzkomponente\Administrator\View\Einsatzberichte;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use EikoNamespace\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\Helpers\ListHelper;


/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView
{

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    protected $form;
    protected $item;
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->params = $this->get('Params');
        $this->pagination = $this->get('Pagination');
        $this->form = $this->get('FilterForm');
        $this->item  = $this->get('Item');
        // Check for errors.
        /* if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors));
        }*/

        EinsatzkomponenteHelper::addSubmenu('einsatzberichte');

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
        $canDo    = EinsatzkomponenteHelper::getActions($this->state->get('filter.category_id'));

        ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'), 'einsatzberichte.png');

        if ($canDo->get('core.create')) {
            ToolbarHelper::addNew('einsatzbericht.add', 'JTOOLBAR_NEW');
        }
        if ($canDo->get('core.edit') && isset($this->items[0])) {
            ToolbarHelper::editList('einsatzbericht.edit', 'JTOOLBAR_EDIT');
        }

        if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
                ToolbarHelper::divider();
                ToolbarHelper::custom('einsatzberichte.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                ToolbarHelper::custom('einsatzberichte.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                ToolbarHelper::deleteList('', 'einsatzberichte.delete', 'JTOOLBAR_DELETE');
            }
            if (isset($this->items[0]->checked_out)) {
                ToolbarHelper::custom('einsatzberichte.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                ToolbarHelper::deleteList('', 'einsatzberichte.delete', 'JTOOLBAR_EMPTY_TRASH');
                ToolbarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                //ToolbarHelper::trash('einsatzberichte.trash','JTOOLBAR_TRASH');
                ToolbarHelper::deleteList('', 'einsatzberichte.delete', 'JTOOLBAR_DELETE');
                ToolbarHelper::divider();
            }
        }
        if ($canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_einsatzkomponente');
            //if (params->get('send_mail_backend','0')) : 
            ToolbarHelper::custom('einsatzberichte.sendMail', 'edit', 'edit', 'COM_EINSATZKOMPONENTE_ALS_EMAIL_VERSENDEN',  true);
            //endif;
        }

        if ($canDo->get('core.create')) {
            ToolbarHelper::custom('einsatzberichte.article', 'edit', 'edit', 'COM_EINSATZKOMPONENTE_ALS_JOOMLA_ARTIKEL_ERSTELLEN',  true);
        }
        ToolbarHelper::custom('einsatzberichte.pdf', 'upload', 'upload', 'COM_EINSATZKOMPONENTE_ALS_PDF_EXPORTIEREN',  true);

        if ($canDo->get('core.create')) :
            if (isset($this->items[0]->state)) {
                ToolbarHelper::divider();
                ToolbarHelper::archiveList('einsatzberichte.archive', 'COM_EINSATZKOMPONENTE_ALS_FOLGEEINSATZ_MARKIEREN');
            }
        endif;


        $this->extra_sidebar = '';

        //Filter for the field auswahl_orga;
        $options = array();
        $form = Form::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');

        $field = $form->getField('auswahl_orga');

        $query = $form->getFieldAttribute('filter_auswahl_orga', 'query');
        $translate = $form->getFieldAttribute('filter_auswahl_orga', 'translate');
        $key = $form->getFieldAttribute('filter_auswahl_orga', 'key_field');
        $value = $form->getFieldAttribute('filter_auswahl_orga', 'value_field');

        // Get the database object.
        $db = Factory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = HTMLHelper::_('select.option', $item->$key, Text::_($item->$value));
                } else {
                    $options[] = HTMLHelper::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        Sidebar::addFilter(
            '$auswahl_orga',
            'filter_auswahl_orga',
            HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.auswahl_orga')),
            true
        );


        //Filter for the field tickerkat;
        $options = array();
        Form::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = Form::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');

        $field = $form->getField('tickerkat');

        $query = $form->getFieldAttribute('filter_tickerkat', 'query');
        $translate = $form->getFieldAttribute('filter_tickerkat', 'translate');
        $key = $form->getFieldAttribute('filter_tickerkat', 'key_field');
        $value = $form->getFieldAttribute('filter_tickerkat', 'value_field');

        // Get the database object.
        $db = Factory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = HTMLHelper::_('select.option', $item->$key, Text::_($item->$value));
                } else {
                    $options[] = HTMLHelper::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        Sidebar::addFilter(
            '$tickerkat',
            'filter_tickerkat',
            HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.tickerkat')),
            true
        );

        //Filter for the field data1;
        $options = array();
        Form::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = Form::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');

        $field = $form->getField('data1');

        $query = $form->getFieldAttribute('filter_data1', 'query');
        $translate = $form->getFieldAttribute('filter_data1', 'translate');
        $key = $form->getFieldAttribute('filter_data1', 'key_field');
        $value = $form->getFieldAttribute('filter_data1', 'value_field');

        // Get the database object.
        $db = Factory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = HTMLHelper::_('select.option', $item->$key, Text::_($item->$value));
                } else {
                    $options[] = HTMLHelper::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        Sidebar::addFilter(
            '$data1',
            'filter_data1',
            HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.data1')),
            true
        );

        //Filter for the field alerting;
        $options = array();
        $form = Form::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');

        $field = $form->getField('alerting');

        $query = $form->getFieldAttribute('filter_alerting', 'query');
        $translate = $form->getFieldAttribute('filter_alerting', 'translate');
        $key = $form->getFieldAttribute('filter_alerting', 'key_field');
        $value = $form->getFieldAttribute('filter_alerting', 'value_field');

        // Get the database object.
        $db = Factory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = HTMLHelper::_('select.option', $item->$key, Text::_($item->$value));
                } else {
                    $options[] = HTMLHelper::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        Sidebar::addFilter(
            '$alerting',
            'filter_alerting',
            HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.alerting')),
            true
        );


        //Filter for the field date1
        $this->extra_sidebar .= '<div class="div_side_filter">';
        $this->extra_sidebar .= '<small><label for="filter_from_date1">ab Datum</label></small>';
        $this->extra_sidebar .= HTMLHelper::_('calendar', $this->state->get('filter.date1.from'), 'filter_from_date1', 'filter_from_date1', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
        $this->extra_sidebar .= '<small><label for="filter_to_date1">bis Datum</label></small>';
        $this->extra_sidebar .= HTMLHelper::_('calendar', $this->state->get('filter.date1.to'), 'filter_to_date1', 'filter_to_date1', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
        $this->extra_sidebar .= '<hr class="hr-condensed">';
        $this->extra_sidebar .= '</div>';


        $options = array();
        $options[] = HTMLHelper::_('select.option', '1', 'JPUBLISHED');
        $options[] = HTMLHelper::_('select.option', '0', 'JUNPUBLISHED');
        $options[] = HTMLHelper::_('select.option', '2', 'COM_EINSATZKOMPONENTE_FOLGEEINSATZ');
        $options[] = HTMLHelper::_('select.option', '*', 'JALL');
        Sidebar::addFilter(
            Text::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            HTMLHelper::_('select.options', $options, "value", "text", $this->state->get('filter.state'), true)
        );


        //Filter for the field created_by
        $this->extra_sidebar .= '<div class="div_side_filter">';
        $this->extra_sidebar .= '<small><label for="filter_created_by">Erstellt von</label></small>';
        $this->extra_sidebar .= ListHelper::users('filter_created_by', $this->state->get('filter.created_by'), 1, 'onchange="this.form.submit();"');
        $this->extra_sidebar .= '</div>';

        //Filter for the field modified_by
        $this->extra_sidebar .= '<div class="div_side_filter">';
        $this->extra_sidebar .= '<small><label for="filter_modified_by">Bearbeitet von</label></small>';
        $this->extra_sidebar .= ListHelper::users('filter_modified_by', $this->state->get('filter.modified_by'), 1, 'onchange="this.form.submit();"');
        $this->extra_sidebar .= '</div>';
    }

    protected function getSortFields()
    {
        return array(
            'a.id' => Text::_('JGRID_HEADING_ID'),
            'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
            'a.alerting' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALERTING'),
            'a.tickerkat' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_TICKERKAT'),
            'a.data1' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATA1'),
            'a.date1' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1'),
            'a.summary' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY'),
            'a.auswahl_orga' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_auswahl_orga'),
            'a.gmap' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_GMAP'),
            'a.status_fb' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_STATUS_FB'),
            'a.updatedate' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_UPDATEDATE'),
            'a.createdate' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATEDATE'),
            'a.status' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_STATUS'),
            'a.counter' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_COUNTER'),
            'a.state' => Text::_('JSTATUS'),
            'a.created_by' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY'),
            'a.modified_by' => Text::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_MODIFIED_BY'),
        );
    }
}
