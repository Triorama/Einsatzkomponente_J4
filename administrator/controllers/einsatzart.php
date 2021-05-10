<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

jimport('joomla.application.component.controllerform');

/**
 * einsatzart controller class.
 */
class EinsatzkomponenteControllereinsatzart extends FormController
{

    function __construct() {
        $this->view_list = 'einsatzarten';
        parent::__construct();
    }

}