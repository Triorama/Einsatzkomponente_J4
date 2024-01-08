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
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;


/**
 * Ausruestung controller class.
 */
class AusruestungController extends FormController
{

    function __construct($config=array(), MVCFactoryInterface $factory=null, $app=null, $input=null) {
        $this->view_list = 'ausruestungen';
        parent::__construct($config, $factory, $app, $input);
    }

}