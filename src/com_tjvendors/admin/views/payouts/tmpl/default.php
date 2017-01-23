<?php
/**
 * @version    SVN:
 * @package    Com_Tjvendors
 * @author     Techjoomla  <contact@techjoomla.com>
 * @copyright  Copyright (c) 2009-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_tjvendors/assets/css/tjvendors.css');
$document->addStyleSheet(JUri::root() . 'media/com_tjvendors/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_tjvendors');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tjvendors&task=payouts.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'payoutList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function ()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}

		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function ()
	{
		jQuery('#clear-search-button').on('click', function ()
		{
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});

	window.toggleField = function (id, task, field)
	{
		var f = document.adminForm,
			i = 0, cbx,
			cb = f[ id ];

		if (!cb) return false;

		while (true)
		{
			cbx = f[ 'cb' + i ];

			if (!cbx) break;

			cbx.checked = false;
			i++;
		}

		var inputField   = document.createElement('input');
		inputField.type  = 'hidden';
		inputField.name  = 'field';
		inputField.value = field;
		f.appendChild(inputField);

		cb.checked = true;
		f.boxchecked.value = 1;
		window.submitform(task);

		return false;
	};

</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_tjvendors&view=payouts&client=' . $this->input->get('client', '', 'STRING')); ?>"
method="post" name="adminForm" id="adminForm">
<?php
if(!empty($this->sidebar))
{?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php
}
else
{?>
	<div id="j-main-container">
<?php
}?>
	<div id="filter-bar" class="btn-toolbar">

		<div class="filter-search btn-group pull-left">
			<label for="filter_search" class="element-invisible">
				<?php echo JText::_('JSEARCH_FILTER'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search"
				placeholder="<?php echo JText::_('COM_TJVENDOR_PAYOUTS_SEARCH_BY_VENDOR_TITLE'); ?>"
				value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				title="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
		</div>


		<div class="btn-group pull-left">
			<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
				<i class="icon-search"></i>
			</button>
			<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
				<i class="icon-remove"></i>
			</button>
		</div>

		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible">
				<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
			</label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>

		<div class="btn-group pull-right hidden-phone">
			<?php // Making custom filter list
				$vendorList[]=JText::_('JFILTER_PAYOUT_ALL_VENDORS');

				foreach ($this->vendor_details as $vendor)
				{
					$vendorList[]=$vendor;
				}
			 echo JHtml::_('select.genericlist', $vendorList, "filter_vendorId", 'class="input-medium" size="1" onchange="jQuery(\'#vendor_id\').val(jQuery(this).val());;jQuery(\'#task\').val(\'payouts.getRedirectToList\');document.adminForm.submit();"', "vendor_id", "vendor_title", $this->state->get('filter.vendor'));?>
		</div>

		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable" class="element-invisible">
				<?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
			</label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
				<option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>>
					<?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?>
				</option>
				<option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>>
					<?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?>
				</option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible">
				<?php echo JText::_('JGLOBAL_SORT_BY'); ?>
			</label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value="">
					<?php echo JText::_('JGLOBAL_SORT_BY'); ?>
				</option>
				<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
			</select>
		</div>
	</div>
	<?php
	if(empty($this->items))
	{?>
		<div class="clearfix">&nbsp;</div>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_TJVENDOR_NO_MATCHING_RESULTS'); ?>
			</div>
	<?php
	}
	else
	{?>
		<table class="table table-striped" id="payoutList">
			<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<?php endif; ?>

					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>

					<?php if (isset($this->items[0]->state)){} ?>

					<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_TJVENDORS_PAYOUTS_VENDOR_ID', 'a.`vendor_id`', $listDirn, $listOrder); ?>
					</th>

					<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_TJVENDORS_PAYOUTS_PAYOUT_TITLE', 'a.`payout_title`', $listDirn, $listOrder); ?>
					</th>

					<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_TJVENDORS_PAYOUTS_CURRENCY', 'a.`currency`', $listDirn, $listOrder); ?>
					</th>

					<th class='left'>
						<?php echo JHtml::_('grid.sort',  'COM_TJVENDORS_PAYOUTS_PAYABLE_AMOUNT', 'a.`payable_amount`', $listDirn, $listOrder); ?>
					</th>

					<th class='left'>
						<?php echo JText::_('COM_TJVENDORS_PAYOUTS_ACTION'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($this->items as $i => $item)
				{
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_tjvendors');
					$canEdit    = $user->authorise('core.edit', 'com_tjvendors');
					$canCheckin = $user->authorise('core.manage', 'com_tjvendors');
					$canChange  = $user->authorise('core.edit.state', 'com_tjvendors');
					?>
					<tr class="row<?php echo $i % 2; ?>">
					<?php
						if (isset($this->items[0]->ordering))
						{?>
							<td class="order nowrap center hidden-phone">
								<?php
								if ($canChange)
								{
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder)
									{
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									}
								?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>" title="<?php echo $disabledLabel ?>">
										<i class="icon-menu"></i>
									</span>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php
								}
								else
								{?>
									<span class="sortable-handler inactive">
										<i class="icon-menu"></i>
									</span>
								<?php
								}?>
							</td>
						<?php
						}?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)){}?>

						<td>
							<?php echo $item->vendor_id; ?>
						</td>

						<td>
								<?php echo $this->escape($item->vendor_title); ?>
						</td>

						<td>
							<?php echo $item->currency; ?>
						</td>

						<td>
							<?php echo $item->total; ?>
						</td>

						<td>
							<a href= "<?php echo JRoute::_('index.php?option=com_tjvendors&view=payout&layout=edit&vendor_id=' .$item->vendor_id.'&id=' .$item->id.'&client=' . $this->input->get('client', '', 'STRING'));?>"
							<button class="validate btn btn-primary">PAY</button>
						</td>
					</tr>
				<?php
				}?>
			</tbody>
		</table>
		<?php
	}?>
		<input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $item->vendor_id; ?>"/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>