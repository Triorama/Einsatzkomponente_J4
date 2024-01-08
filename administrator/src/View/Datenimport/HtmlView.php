<?php 
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
namespace EikoNamespace\Component\Einsatzkomponente\Administrator\View\Datenimport;
// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
class HtmlView extends BaseHtmlView
{
  function display($tpl = null) 
  {
    $this->addToolBar();
 
    // Display the template
    parent::display($tpl);
  }
        
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		ToolbarHelper::title(Text::_('Datenimport früherer Versionen'), 'upload');
	}
	
	
}
?>
