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
\defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use EikoNamespace\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
/**
 * Einsatzbericht controller class.
 */
class EinsatzberichtController extends FormController
{
    function __construct() {
        $this->view_list = 'einsatzberichte';
        parent::__construct();
    }
    public function pdf() {
    	// Check for request forgeries
	Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
		
    	$cid = Factory::getApplication()->input->get('id', array(), 'array');
    	if (!is_array($cid) || count($cid) < 1)
	{
	    Factory::getApplication()->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'error');
	}
	else
	{
    	    $msg = EinsatzkomponenteHelper::pdf($cid);
	    $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$cid[0], $msg);
	}
    }
 
    	function save2copy($key = NULL, $urlVar = NULL) {
		// Check for request forgeries
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
		
		}
 
    	function save($key = NULL, $urlVar = NULL) {
		// Check for request forgeries
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$send = 'false';
		$cid = Factory::getApplication()->input->get('id','0');
		$params = ComponentHelper::getParams('com_einsatzkomponente');

		if (parent::save()) :
		
		// Wasserzeichen speichern
		$input = Factory::getApplication()->input;
		// Get the form data
		$formData = new Input($input->get('jform', '', 'array'));
		// Get any data being able to use default values
		$watermark_image = $formData->getString('watermark_image');

		$params = ComponentHelper::getParams('com_einsatzkomponente');
		// Set new value of param(s)
		$params->set('watermark_image', $watermark_image);

		// Save the parameters
		$componentid = ComponentHelper::getComponent('com_einsatzkomponente')->id;
		$table = Table::getInstance('extension');
		$table->load($componentid);
		$table->bind(array('params' => $params->toString()));

		// check for error
		if (!$table->check()) {
			echo $table->getError();
			return false;
		}
		// Save to database
		if (!$table->store()) {
			echo $table->getError();
			return false;
		}
		// Wasserzeichen speichern  ENDE
		
		// Bilder upload
		$files        = Factory::getApplication()->input->files->get('data', '', 'array');
		
		if(!$files['0']['name'] =='') : 
		if (!$cid) :
		$db = Factory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id; 
		endif;
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/upload.php'; // Helper-class laden
			endif;		

		$automail_off = $formData->getString('automail_off');

				if ( $params->get('send_mail_auto', '0') ): 
				if ( !$automail_off ): 
		if (!$cid) :
		$db = Factory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id;
		$send = sendMail_auto($cid,'neuer Bericht: ');
		else:
		$send = sendMail_auto($cid,'Update: ');
		endif;
		endif;
		endif;
	endif;
    //print_r ($send);break;
    }
}
	    function sendMail_auto($cid,$status) {

		
		$input = Factory::getApplication()->input;
		// Get the form data
		$formData = new Input($input->get('jform', '', 'array'));
		// Get any data being able to use default values
		$emailtext = $formData->getString('emailtext');

		//$model = $this->getModel();
		$params = ComponentHelper::getParams('com_einsatzkomponente');
		$user = Factory::getUser();
		$query = 'SELECT * FROM #__eiko_einsatzberichte WHERE id = "'.$cid.'" LIMIT 1';
		$db = Factory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		$mailer = Factory::getMailer();
		$config = Factory::getConfig();
		
		$sender = array( 
    	$user->email,
    	$user->name );
		
		$mailer->setSender($sender);
		
		$user = Factory::getUser();
		$recipient = $params->get('mail_empfaenger_auto',$user->email);
		
		$recipient 	 = explode( ',', $recipient);
		
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = Factory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('#__eiko_organisationen')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

					$orga		 = explode( ',', $auswahl_orga);
 
		$mailer->addRecipient($recipient);
		
		$mailer->setSubject($status.''.$orga[0].'  +++ '.$result[0]->summary.' +++');
		
		$db = Factory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('#__eiko_tickerkat')
						->where('id = "' .$result[0]->tickerkat.'"  AND state = "1" ');
					$db->setQuery($query);
					$kat = $db->loadObject();
		
		$link = Route::_( uri::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id.'&Itemid='.$params->get('homelink','')); 
		
		$body   = '';
			if ($emailtext) :
			$body   .= $emailtext.'<hr>';
			endif;
		$body	. '<h2>+++ '.$result[0]->summary.' +++</h2>';
		if ($params->get('send_mail_kat','0')) :	
		$body   .= '<h4>'.Text::_($kat->title).'</h4>';
		endif;
		if ($params->get('send_mail_orga','0')) :	
		$body   .= '<span><b>Eingesetzte Kräfte:</b> '.$orgas.'</span>';
		endif;
		$body   .= '<div>';
		if ($params->get('send_mail_desc','0')) :	
		if ($result[0]->desc) :	
    	$body   .= '<p>'.$result[0]->desc.'</p>';
		else:
    	$body   .= '<p>Ein ausführlicher Bericht ist zur Zeit noch nicht vorhanden.</p>';
		endif;
		endif;
		if ($params->get('send_mail_link','0')) :	
    	$body   .= '<p><a href="'.$link.'" target="_blank">Link zur Homepage</a></p>';
		endif;
		if ($result[0]->image) :	
		if ($params->get('send_mail_image','0')) :	
		$body   .= '<img src="'.uri::root().$result[0]->image.'" style="margin-left:10px;float:right;height:50%;" alt="Einsatzbild"/>';
		endif;
		endif;
		$body   .= '</div>';
		

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		// Optionally add embedded image
		//$mailer->AddEmbeddedImage( JPATH_COMPONENT.'/assets/logo128.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );
		
		$send = $mailer->Send();
        return 'gesendet'; 
    }
	
