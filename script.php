<?php
/**
 * @version     4.0.00
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2022 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

// No direct access
defined('_JEXEC') or die();
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\Adapter\ComponentAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

class Com_EinsatzkomponenteInstallerScript
{
  private $minimumJoomlaVersion = '4.0';
  private $minimumPHPVersion = JOOMLA_MINIMUM_PHP;
  public function install(InstallerAdapter $parent): bool
  {
    // $parent is the class calling this method
    $parent->getParent()->setRedirectUrl('index.php?option=com_einsatzkomponente&view=installation');
    return true;
  }
  public function uninstall(InstallerAdapter $parent): bool
  {
    echo '<h1>Die Datenbanktabellen müssen Sie manuell löschen ...</h1>';
    return true;
  }

  public function update(InstallerAdapter $parent): bool
  {
    // $parent is the class calling this method
    $parent->getParent()->setRedirectUrl('index.php?option=com_einsatzkomponente&view=installation');
    return true;
  }

  function preflight($type, InstallerAdapter $parent): bool
  {
    if ($type !== 'uninstall') {
      // Check for the minimum PHP version before continuing
      if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
        Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPHPVersion), Log::WARNING, 'jerror');

        return false;
      } // Check for the minimum Joomla version before continuing
      if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
        Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomlaVersion), Log::WARNING, 'jerror');

        return false;
      }
    }

    echo Text::_('COM_EINSATZKOMPONENTE_INSTALLERSCRIPT_PREFLIGHT');

    return true;
  }

  public function postflight($type, $parent)
  {
  }
}
