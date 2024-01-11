<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;

$params = ComponentHelper::getParams('com_einsatzkomponente');
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');

// Daten aus der Bilder-Galerie holen
if (!$this->item->id == 0) {
  $db = Factory::getDBO();
  $query = 'SELECT id, thumb, comment FROM #__eiko_images WHERE report_id="' . $this->item->id . '" AND state="1" ORDER BY ordering ASC';
  $db->setQuery($query);
  $rImages = $db->loadObjectList();
}
?>
<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="einsatzbericht-form" class="form-validate">
	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', [
    'active' => 'general',
    'recall' => true,
    'breakpoint' => 768,
  ]); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_EINSATZKOMPONENTE_EINSATZ_INHALT')); ?>
		<div class="row">
			<div class="col-lg-4">
				<div>
					<?php if (Factory::getUser()->authorise('core.admin', 'einsatzkomponente')): ?>
						<?php echo $this->form->renderField('counter'); ?>
					<?php endif; ?>
						<?php echo $this->form->renderField('alarmierungsart'); ?>
						<?php echo $this->form->renderField('einsatzart'); ?>
						<?php echo $this->form->renderField('einsatzkategorie'); ?>
						<?php echo $this->form->renderField('address'); ?>
						<?php echo $this->form->renderField('alarmierungszeit'); ?>
						<?php echo $this->form->renderField('ausfahrtszeit'); ?>
						<?php echo $this->form->renderField('einsatzende'); ?>
				</div>
			</div>
			<div class="col-lg-4">
					<h1>Einsatzkräfte :</h1>
						<?php echo $this->form->renderField('einsatzleiter'); ?>
						<?php echo $this->form->renderField('einsatzfuehrer'); ?>
						<?php echo $this->form->renderField('people'); ?>
						<?php echo $this->form->renderField('auswahl_orga'); ?>
						<?php echo $this->form->renderField('vehicles'); ?>
			
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_EINSATZKOMPONENTE_EINSATZ_OPTIONEN')); ?>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_EINSATZKOMPONENTE_EINSATZ_OPTIONEN')); ?>
		<div class="row">
			<div class="col-lg-4">
				<div>
					<fieldset class="adminform">
							<?php echo $this->form->renderField('id'); ?>
					</fieldset>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type='hidden' name="action" value="Filedata" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>