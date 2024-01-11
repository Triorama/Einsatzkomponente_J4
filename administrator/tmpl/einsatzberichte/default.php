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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use EikoNamespace\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
// HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('bootstrap.renderModal', 'a.modal');
HTMLHelper::_('bootstrap.tooltip');
// HTMLHelper::_('formbehavior.chosen', 'select');
// HTMLHelper::_('stylesheet', 'administrator/components/com_einsatzkomponente/assets/css/einsatzkomponente.css');

$params = ComponentHelper::getParams('com_einsatzkomponente');

$user = Factory::getUser();
$userId = $user->get('id');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_einsatzkomponente');
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
  $saveOrderingUrl = 'index.php?option=com_einsatzkomponente&task=einsatzberichte.saveOrderAjax&tmpl=component';
  HTMLHelper::_('sortablelist.sortable', 'einsatzberichtList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

$script =
  "			
	Joomla.orderTable = function() {
		table = document.getElementById('sortTable');
		direction = document.getElementById('directionTable');
		order = table.options[table.selectedIndex].value;
		if (order != '" .
  $listOrder .
  "') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
			";

Factory::getDocument()->addScriptDeclaration($script);

//code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
  $this->sidebar .= $this->extra_sidebar;
}
?>
<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&view=einsatzberichte'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php else: ?>
			<div id="j-main-container">
			<?php endif; ?>

			<div id="filter-bar" class="btn-toolbar">


				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo Text::_('JSEARCH_FILTER'); ?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo Text::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo Text::_('JSEARCH_FILTER'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button class="btn hasTooltip" type="button" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>

				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></label>
					<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php if ($listDirn == 'asc') {
        echo 'selected="selected"';
      } ?>><?php echo Text::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
						<option value="desc" <?php if ($listDirn == 'desc') {
        echo 'selected="selected"';
      } ?>><?php echo Text::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo Text::_('JGLOBAL_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo Text::_('JGLOBAL_SORT_BY'); ?></option>
						<?php echo HTMLHelper::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>

			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="einsatzberichtList">
				<thead>
					<tr>
						<?php if (isset($this->items[0]->ordering)): ?>
							<th width="1%" class="nowrap center hidden-phone">
								<?php echo HTMLHelper::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
							</th>
						<?php endif; ?>
						<th width="1%" class="">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<?php if (isset($this->items[0]->state)): ?>
							<th width="1%" class="nowrap center">
								<?php echo HTMLHelper::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>

						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALARMIERUNGSZEIT', 'a.alarmierungszeit', $listDirn, $listOrder); ?>
						</th>
						<!-- Spalte Alarmierungsart wurde mit Spalte Einsatzart zusammen gelegt			
                <th>
				<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALARMIERUNGSART', 'a.alarmierungsart', $listDirn, $listOrder); ?>
				</th>
-->
						<th class='left backend_einsatzkategorie'>
							<?php echo HTMLHelper::_('grid.sort', 'Kat', 'a.einsatzkategorie', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_EINSATZART', 'a.einsatzart', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
						</th>
						<th class='left backend_einsatzfoto'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY', 'a.summary', $listDirn, $listOrder); ?>
						</th>
						<!--				<th class='left'>
				<?php
//echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DEPARTMENT', 'a.department', $listDirn, $listOrder);
?>
				</th>
-->
						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ZUGRIFFE', 'a.counter', $listDirn, $listOrder); ?>
						</th>

						<?php if ($params->get('gmap_action', '0')): ?>
							<th class='left'>
								<?php echo HTMLHelper::_('grid.sort', '<small>GMap</small>', 'a.gmap', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>

						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATEDATE', 'a.createdate', $listDirn, $listOrder); ?>
						</th>

						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_UPDATEDATE', 'a.updatedate', $listDirn, $listOrder); ?>
						</th>

						<?php if ($params->get('info112', '0')): ?>
							<th class='left'>
								<?php echo HTMLHelper::_('grid.sort', '<small>Info112.net</small>', 'a.notrufticker', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>

						<th class='left'>
							<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_AUSWAHLORGA', 'a.auswahl_orga', $listDirn, $listOrder); ?>
						</th>
						<!--			<th class='left'>
				<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('grid.sort', 'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_MODIFIED_BY', 'a.modified_by', $listDirn, $listOrder); ?>
				</th>
				-->

						<?php if (isset($this->items[0]->id)): ?>
							<th width="1%" class="nowrap center hidden-phone">
								<?php echo HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->items as $i => $item):

       $ordering = $listOrder == 'a.ordering';
       $canCreate = $user->authorise('core.create', 'com_einsatzkomponente');
       $canEdit = $user->authorise('core.edit', 'com_einsatzkomponente');
       $canCheckin = $user->authorise('core.manage', 'com_einsatzkomponente');
       $canChange = $user->authorise('core.edit.state', 'com_einsatzkomponente');
       ?>
						<tr class="row<?php echo $i % 2; ?>">

							<?php if (isset($this->items[0]->ordering)): ?>
								<td class="order nowrap center hidden-phone">
									<?php if ($canChange):

           $disableClassName = '';
           $disabledLabel = '';
           if (!$saveOrder):
             $disabledLabel = Text::_('JORDERINGDISABLED');
             $disableClassName = 'inactive tip-top';
           endif;
           ?>
										<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
											<i class="icon-menu"></i>
										</span>
										<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
									<?php
         else:
            ?>
										<span class="sortable-handler inactive">
											<i class="icon-menu"></i>
										</span>
									<?php
         endif; ?>
								</td>
							<?php endif; ?>
							<td class="center ">
								<?php $curTime = strtotime($item->alarmierungszeit); ?>
								<?php echo '<small>#' . EinsatzkomponenteHelper::ermittle_einsatz_nummer($curTime, $item->einsatzart_id) . '/' . date('Y', $curTime) . '</small>'; ?>
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>

							</td>
							<?php if (isset($this->items[0]->state)): ?>
								<td class="center">
									<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'einsatzberichte.', $canChange, 'cb'); ?>
								</td>
							<?php endif; ?>
							<td>
								<?php echo $item->alarmierungszeit; ?>
							</td>

							<?php // Get Einsatzkategorie


       $database = Factory::getDBO();
       $query = 'SELECT * FROM #__eiko_einsatzkategorie WHERE title = "' . $item->einsatzkategorie . '" LIMIT 1 ';
       $database->setQuery($query);
       $kat = $database->loadObject();
       ?>
							<td class='backend_einsatzkategorie'>
								<?php echo '<img src="../' . $kat->image . '" width="32" height="100%" title="' . $kat->title . '" />'; ?>
							</td>

							<td>
								<?php // Get Image of Alarmierungsart


        $database = Factory::getDBO();
        $query = 'SELECT * FROM #__eiko_alarmierungsarten WHERE title = "' . $item->alarmierungsart . '" LIMIT 1 ';
        $database->setQuery($query);
        $alarmierungsart_image = $database->loadObject();
        ?>
								<?php // Get color of Einsatzart


        $database = Factory::getDBO();
        $query = 'SELECT * FROM #__eiko_einsatzarten WHERE title = "' . $item->einsatzart . '" LIMIT 1 ';
        $database->setQuery($query);
        $einsatzart = $database->loadObject();
        ?>
								<?php if (isset($item->checked_out) && $item->checked_out): ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzberichte.', $canCheckin); ?>
								<?php endif; ?>


								<div style="border-left:6px solid;border-color:<?php echo $einsatzart->marker; ?>;padding-left:3px;">
									<?php if ($canEdit): ?>
										<a href="<?php echo Route::_('index.php?option=com_einsatzkomponente&task=einsatzbericht.edit&id=' . (int) $item->id); ?>">
										<?php endif; ?>
										<?php echo '<b>' . $einsatzart->title . '</b>'; ?>
										<?php if ($canEdit): ?>
										</a>
									<?php endif; ?>
								</div>
								<?php
        echo '<div style="padding-top:5px;">';
        if (isset($alarmierungsart_image->image)):
          echo '<img src="../' . $alarmierungsart_image->image . '" class="backend_alarmierungsart_style" title ="' . $alarmierungsart_image->title . '" />';
        endif;
        if (isset($einsatzart->list_icon)):
          echo '&nbsp;<img src="../' . $einsatzart->list_icon . '" class="backend_data_style" title ="' . $einsatzart->title . '" />';
        endif;
        if (isset($kat->image)):
          echo '&nbsp;<img src="../' . $kat->image . '" class="backend_kat_style" title="' . $kat->title . '" />';
        endif;
        if ($item->image):
          echo '&nbsp;<img src="../' . $item->image . '" class="backend_foto_style" title="' . $item->image . '"/>';
        endif;
        echo '</div>';
        ?>

							</td>
							<td>
								<?php echo $this->escape($item->address); ?>
							</td>
							<td class='backend_einsatzfoto'>
								<?php echo '<span style="float:left;"> <img src="../' . $item->image . '" width="30" height="100%" title="' . $item->image . '"/></span>'; ?>
							</td>
							<td>
								<?php echo $item->summary; ?>
							</td>
							<!--				<td>
					<?php
       //echo $item->department;
       ?>
				</td>
-->
							<td>
								<?php echo '<span class="badge bg-info text-dark">' . $item->counter . '</span>'; ?>
							</td>

							<?php if ($params->get('gmap_action', '0')): ?>
								<?php
        $gmap = $item->gmap;

        if ($gmap == '1') {
          echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/ok.png" width="24" height="24" /></td>';
        }

        if ($gmap == '0') {
          echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/error.png" width="24" height="24" /></td>';
        }
        ?>
							<?php endif; ?>

							<td>
								<?php echo '<span class="badge bg-info text-dark">' . $item->createdate . '</span><br/><span style="color:#aaaaaa;">' . $item->created_by . '</span>'; ?>
							</td>
							<td>
								<?php echo '<span class="badge bg-info text-dark">' . $item->updatedate . '</span><br/><span style="color:#aaaaaa;">' . $item->modified_by . '</span>'; ?>
							</td>
							<?php
       // ----------------  info112.net  ----------------------------------------

       //    if ($this->params->get('info112', '0')):
       //      $notrufticker = $item->notrufticker;

       //      if ($notrufticker == '1') {
       //        echo '<td align="center" nowrap="nowrap"><a class="link" id="link" href="' .
       //          Route::_('index.php?option=com_einsatzkomponente') .
       //          '&view=einsatzberichte&tickerID2=' .
       //          $item->id .
       //          '"><img src="../administrator/components/com_einsatzkomponente/assets/images/send.png" width="32" height="32" /></a></td>';
       //      }

       //      if ($notrufticker == '2') {
       //        echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/ok.png" width="24" height="24" /></td>';
       //      }

       //      if ($notrufticker == '0') {
       //        echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/error.png" width="24" height="24" /></td>';
       //      }
       //    endif;
       // ----------------  info112.net  ENDE ----------------------------------------
       ?>
							<td>
								<?php echo $item->auswahl_orga; ?>
							</td>

							<!--
				<td>
					<?php echo $item->created_by; ?>
				</td>
				<td>
					<?php echo $item->modified_by; ?>
				</td>
				-->

							<?php if (isset($this->items[0]->id)): ?>
								<td class="center hidden-phone">
									<?php echo (int) $item->id; ?>
								</td>
							<?php endif; ?>
						</tr>
					<?php
     endforeach; ?>
				</tbody>
			</table>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
			</div>
</form>