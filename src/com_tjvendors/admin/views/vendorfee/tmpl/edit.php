<?php
/**
 * @version    SVN:
 * @package    Com_Tjvendors
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  Copyright (c) 2009-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();


?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function ()
	{
	});
		

	Joomla.submitbutton = function (task)
	{
		if(task == 'vendor.apply' || task == 'vendor.save' || task == 'vendor.save2new' || task == 'vendor.save2copy')
		{
			var username = document.getElementById("jform_user_id").value;

			if(username == 'Select a User.')
			{
				var msg = "<?php echo JText::_('COM_TJVENDORS_SELECT_USERNAME'); ?>";
				alert(msg);
				return false;
			}
			else
			{
				Joomla.submitform(task, document.getElementById('vendor-form'));
			}
		}
		else if (task == 'vendor.cancel')
		{
			Joomla.submitform(task, document.getElementById('vendor-form'));
		}
		else
		{
			Joomla.submitform(task, document.getElementById('vendor-form'));
		}
	}
</script>

<form action="
<?php echo JRoute::_('index.php?option=com_tjvendors&layout=edit&fee_id=' . (int) $this->fee_id . '&vendor_id=' . (int) $this->item->vendor_id);?>"
method="post" enctype="multipart/form-data" name="vendor-form" id="vendor-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'personal')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'personal', JText::_('COM_TJVENDORS_TITLE_PERSONAL', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
					

						<?php echo $this->form->renderField('vendor_title'); ?>
						<div class = "control-group" >
							<div class = "control-label">
						<label><?php echo JText::_('COM_TJVENDORS_FORM_LBL_VENDOR_CURRENCY'); ?></label>
						</div>
						<div class = "controls">
						
						<?php
						echo $this->curr;
						?>
						</div>
						</div>
						<?php echo $this->form->renderField('percent_commission'); ?>
						<?php echo $this->form->renderField('flat_commission'); ?>
						<?php 
						if ($this->state->params->get('save_history', 1))
						{
							?>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('version_note');?></div>
								<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
							</div>
							<?php
						}
						?>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="jform[currency]" value="<?php echo $this->curr;?>" />
		<input type="hidden" name="jform[vendor_id]" value="<?php echo $this->vendor_id;?>" />
		<input type="hidden" name="jform[fee_id]" value="<?php echo $this->fee_id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
