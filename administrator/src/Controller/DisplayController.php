<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace EikoNamespace\Component\Einsatzkomponente\Administrator\Controller;

// No direct access
\defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;

class DisplayController extends BaseController
{
  /**
   * The default view for the display method.
   *
   * @var string
   */
  protected $default_view = 'einsatzkomponente';

  public function display($cachable = false, $urlparams = [])
  {
    return parent::display($cachable, $urlparams);
  }
  /**
   * Method to display a view.
   *
   * @param	boolean			$cachable	If true, the view output will be cached
   * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
   *
   * @return	JController		This object to support chaining.
   * @since	1.5
   */
  /*
	public function display($cachable = false, $urlparams = false)
	{
		
		
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
		
		$params = ComponentHelper::getParams('com_einsatzkomponente');
		
		$db = Factory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
		$parameter = json_decode( $db->loadResult(), true );
        $version = $parameter['version'];

		// Version auf BETA überprüfen, und gegebenenfalls eine Warnung ausgeben
        if($version!=str_replace("beta","",$version)):
		?>
		<table>
		<tr>
		<div class="alert alert-info j-toggle-main " style="">
		<h4>Hinweis :</h4>Achtung Beta-Version <?php echo $parameter['version'];?> !!! Es wird nicht empfohlen, diese Version der Einsatzkomponente auf einer öffentlichen Live-Webseite zu betreiben. 
		</div>        
		</tr>
		</table>
		<?php endif; ?>
		

<?php
		//------------------------------------------------------------------------
        if($version!=str_replace("Premium","",$version)):
		$params->set('eiko', '1');
		endif;  ?>
		
<?php if (!$params['eiko']) : ?>	 	
		<table>
		<tr>
		<div class="alert alert-danger" style="">
		<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HDFKVJSKSEFY"><span style="float:left;"><img border=0  width="150px" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" /></span></a>
		Unterstützen Sie bitte die Weiterentwicklung unseres Projekts EINSATZKOMPONENTE mit einer Spende, damit wir unsere Software auch weiterhin kostenlos und werbefrei zur Verfügung stellen können.<br/><small>Dieses Fenster wird nach der Eingabe des Validationsschlüssel automatisch ausgeblendet</small>.  
		</div>        
		</tr>
		</table>
<?php endif; ?>

<?php	// Catch Sites 

		$j_version = new Version;
		$response = @file("https://einsatzkomponente.joomla100.com/gateway/validation.php?validation=".$params->get('validation_key','0')."&domain=".$_SERVER['SERVER_NAME']."&version=".$j_version->getShortVersion()."&eikoversion=".$version); // Request absetzen

?>

<?php	

		$view		= Factory::getApplication()->input->getCmd('view', 'kontrollcenter');
        Factory::getApplication()->input->set('view', $view);
		parent::display($cachable, $urlparams);
		return $this;
	}*/
}
