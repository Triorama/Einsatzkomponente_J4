<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_einsatzkomponente
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die();

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use EikoNamespace\Component\Einsatzkomponente\Administrator\Extension\EinsatzkomponenteComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

/**
 * The foos service provider.
 * https://github.com/joomla/joomla-cms/pull/20217
 *
 * @since  __BUMP_VERSION__
 */

return new class implements ServiceProviderInterface {
  /**
   * Registers the service provider with a DI container.
   *
   * @param   Container  $container  The DI container.
   *
   * @return  void
   *
   * @since   __BUMP_VERSION__
   */
  public function register(Container $container)
  {
    $container->registerServiceProvider(new CategoryFactory('\\EikoNamespace\\Component\\Einsatzkomponente'));
    $container->registerServiceProvider(new MVCFactory('\\EikoNamespace\\Component\\Einsatzkomponente'));
    $container->registerServiceProvider(new ComponentDispatcherFactory('\\EikoNamespace\\Component\\Einsatzkomponente'));

    $container->set(ComponentInterface::class, function (Container $container) {
      $component = new EinsatzkomponenteComponent($container->get(ComponentDispatcherFactoryInterface::class));
      $component->setMVCFactory($container->get(MVCFactoryInterface::class));

      return $component;
    });
  }
};
