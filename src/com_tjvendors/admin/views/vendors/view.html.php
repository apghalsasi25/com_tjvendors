<?php
/**
 * @version    SVN:
 * @package    Com_Tjvendors
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  Copyright  2009-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
JLoader::import('com_tjvendors.helpers.fronthelper', JPATH_SITE . '/components');

/**
 * View class for a list of Tjvendors.
 *
 * @since  1.6
 */
class TjvendorsViewVendors extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->input = JFactory::getApplication()->input;
		$this->params = JComponentHelper::getParams('com_tjvendors');
		JText::script('COM_TJVENDOR_VENDOR_APPROVAL');
		JText::script('COM_TJVENDOR_VENDOR_DENIAL');

		$this->vendorApproval = $this->params->get('vendor_approval');
		$client = $this->input->get('client', '', 'STRING');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		TjvendorsHelper::addSubmenu('vendors');

		$this->addToolbar();

		if (!empty($client))
		{
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$this->client = $input->get('client', '', 'STRING');

		$state = $this->get('State');
		$canDo = TjvendorsHelper::getActions();
		JToolBarHelper::addNew('vendor.add');

		$tjvendorFrontHelper = new TjvendorFrontHelper;
		$clientTitle = $tjvendorFrontHelper->getClientName($this->client);
		JToolbarHelper::title($clientTitle . ' : ' . JText::_('COM_TJVENDORS_TITLE_VENDORS'), 'list.png');

		if ($canDo->get('core.edit') && isset($this->items[0]))
		{
			JToolBarHelper::editList('vendor.edit', 'JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('vendors.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('vendors.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}

			if (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'vendors.delete', 'JTOOLBAR_DELETE');
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_tjvendors');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_tjvendors&view=vendors');

		$this->extra_sidebar = '';
	}
}
