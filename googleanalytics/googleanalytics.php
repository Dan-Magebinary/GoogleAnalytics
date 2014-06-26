<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

 class Googleanalytics extends Module
 {
	 /**
	  * initiate Google Analytics module
	  */
	 public function __construct()
	 {
		 $this->name = 'googleanalytics';
		 $this->tab = 'analytics_stats';
		 $this->version = '1.0';
		 $this->author = 'Dan Chen(dan@magebin.com)';
		 $this->need_instance = 1;
		 $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		 $this->bootstrap = true;

		 parent::__construct();

		 $this->displayName = $this->l('Google Analytics');
		 $this->description = $this->l('This is the GoogleAnalytics extension for Prestashop');

		 $this->confirmUninstall = $this->l('Are you sure you want to uninstall GoogleAnalytics?');

		 if (!Configuration::get('GOOGLEANALYTICS_NAME'))
			 $this->warning = $this->l('No name provided');
	 }

	 /**
	  * install module
	  * @return bool
	  */
	 public function install()
	 {
		 if (Shop::isFeatureActive())
			 Shop::setContext(Shop::CONTEXT_ALL);

		 if (!parent::install() ||
			 !$this->registerHook('header') ||
			 !$this->registerHook('adminOrder') ||
			 !$this->registerHook('footer') ||
			 !$this->registerHook('home') ||
			 !$this->registerHook('productfooter') ||
			 !$this->registerHook('shoppingCart') ||
			 !$this->registerHook('top') ||
			 !Configuration::updateValue('MYMODULE_NAME', 'my friend')
		 )
			 return false;

		 //drop transaction table
		 Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'googleanalytics`');
		 //create transaction table
		 $query = 'CREATE TABLE `'._DB_PREFIX_.'googleanalytics` (
			 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`id_order` INT NOT NULL ,
			`sent` Boolean,
			`date_added` DateTime
			)';

		if(!Db::getInstance()->Execute($query))
		{
			$this->uninstall();
			return false;
		}

		 return true;
	 }

	 /**
	  * uninstall module
	  * @return bool
	  */
	 public function uninstall()
	 {
		 if (!parent::uninstall())
			 return false;
		 //drop transaction table
		 Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'googleanalytics`');
		 return true;
	 }

	 /**
	  * back office module configuration page content
	  */
	 public function getContent()
	 {
		$output = '';
    if (Tools::isSubmit('submit'.$this->name))
		{
			$my_module_name = strval(Tools::getValue('MYMODULE_NAME'));
			 if (!$my_module_name  || empty($my_module_name) || !Validate::isGenericName($my_module_name))
				 $output .= $this->displayError( $this->l('Invalid Configuration value') );
			 else
			 {
				 Configuration::updateValue('MYMODULE_NAME', $my_module_name);
				 $output .= $this->displayConfirmation($this->l('Settings updated'));
			 }
		}
    return $output.$this->displayForm();
	 }

	 /**
	  * hook page header to add CSS and JS files
	  */
	 public function hookDisplayHeader()
	 {

	 }

	 /**
	  * back office return configuration form
	  * @return mixed
	  */
	 public function displayForm()
	 {
		 // Get default Language
		 $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		 // Init Fields form array
		 $fields_form[0]['form'] = array(
			 'legend' => array(
				 'title' => $this->l('Settings'),
			 ),
			 'input' => array(
				 array(
					 'type' => 'text',
					 'label' => $this->l('Configuration value'),
					 'name' => 'MYMODULE_NAME',
					 'size' => 20,
					 'required' => true
				 )
			 ),
			 'submit' => array(
				 'title' => $this->l('Save'),
				 'class' => 'button'
			 )
		 );

		 $helper = new HelperForm();

		 // Module, t    oken and currentIndex
		 $helper->module = $this;
		 $helper->name_controller = $this->name;
		 $helper->token = Tools::getAdminTokenLite('AdminModules');
		 $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		 // Language
		 $helper->default_form_language = $default_lang;
		 $helper->allow_employee_form_lang = $default_lang;

		 // Title and toolbar
		 $helper->title = $this->displayName;
		 $helper->show_toolbar = true;        // false -> remove toolbar
		 $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		 $helper->submit_action = 'submit'.$this->name;
		 $helper->toolbar_btn = array(
			 'save' =>
				 array(
					 'desc' => $this->l('Save'),
					 'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
						 '&token='.Tools::getAdminTokenLite('AdminModules'),
				 ),
			 'back' => array(
				 'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				 'desc' => $this->l('Back to list')
			 )
		 );

		 // Load current value
		 $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');

		 return $helper->generateForm($fields_form);



	 }

	 /**
	  * hook admin order to send transactions and refunds details
	  */
	 public function displayAdminOrder()
	 {



	 }

	 /**
	  * hook footer to load JS script for standards actions such as product clicks
	  */
	 public function displayFooter()
	 {

	 }

	 /**
	  * hook home to display generate the product list associated to home featured, news products and best sellers Modules
	  */
	 public function displayHome()
	 {


	 }

	 /**
	  * hook product page footer to load JS for product details view
	  */
	 public function displayFooterProduct()
	 {


	 }

	 /**
	  * hook shopping cart footer to send the checkout details
	  */
	 public function displayShoppingCartFooter()
	 {


	 }

	 /**
	  * hook top to track transactions
	  */
	 public function displayTop()
	 {


	 }




 }