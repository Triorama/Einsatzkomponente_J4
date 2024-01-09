<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
namespace EikoNamespace\Component\Einsatzkomponente\Administrator\Extension;

// no direct access
defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
//use EikoNamespace\Component\Einsatzkomponente\Administrator\Service\HTML\AdministratorService;
use Psr\Container\ContainerInterface;

class EinsatzkomponenteComponent extends MVCComponent implements
  BootableExtensionInterface,
  CategoryServiceInterface
{
  use CategoryServiceTrait;
  use HTMLRegistryAwareTrait;

  /**
   * Booting the extension. This is the function to set up the environment of the extension like
   * registering new class loaders, etc.
   *
   * If required, some initial set up can be done from services of the container, eg.
   * registering HTML services.
   *
   * @param   ContainerInterface  $container  The container
   *
   * @return  void
   *
   * @since   __BUMP_VERSION__
   */

  public function boot(ContainerInterface $container)
  {
    //$this->getRegistry()->register('einsatzkomponenteAdministrator', new AdministratorService);
  }
}
