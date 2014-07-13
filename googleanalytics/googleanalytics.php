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
		 $this->version = '0.9(beta)';
		 $this->author = 'Dan(dan@magebin.com)';
		 $this->need_instance = 1;
		 $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		 $this->bootstrap = true;

		 parent::__construct();

		 $this->displayName = $this->l('Google Analytics');
		 $this->description = $this->l('This is the GoogleAnalytics extension for Prestashop, using enhanced e-commerce Google Analytics API');

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
			 !$this->registerHook('top') 
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
	  * back office return configuration form
	  * @return mixed
	  */
	 public function displayForm()
	 {
		 // Get default Language
		 $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		
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

		  // Init Fields form array
		 $fields_form[0]['form'] = array(
			 'legend' => array(
				 'title' => $this->l('Google Analytics General Settings'),
			 ),
			 'input' => array(
			 //add enable&disable switch
				  array(
					'type' => 'switch',
					'label' => $this->l('Enable Google Analytics Module'),
					'name' => 'googleanalytics_enable',
					'is_bool' => true,
					'required' => true,
					'values' => array(
						array(
							'id' => 'googleanalytics_enable_yes',
							'value' => 1,
							'label' => $this->l('Yes'),
						),
						array(
							'id' => 'googleanalytics_enable_no',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
					'hint' => $this->l('Enable or disable Google Analytics')
				),
			  //add google analytics 
				array(
					 'type' => 'text',
					 'label' => $this->l('Web Tracking Id'),
					 'name' => 'googleanalytics_webtrackingid',
					 'size' => 20,
					 'required' => true,
					 'hint'=>'Get tracking Id from google'
				 ),
			 
				 array(
					 'type' => 'radio',
					 'label' => $this->l('Label1'),
					 'name' => 'label1',
					 'size' => 20,
					 'required' => true,
					 'values' => array(
						array(
							'id' => '1',
							'value' => '1',
							'label' => $this->l('111'),
							'hint' => $this->l('111111'),
						),
						array(
							'id' => '2',
							'value' => '2',
							'label' => $this->l('222'),
							'hint' => $this->l('2222'),
						),
					),
				 ),
				
			 ),
			 'submit' => array(
				 'title' => $this->l('Save'),
				 'class' => 'button'
			 )
		 );
		  // Load current value
		 $helper->fields_value['googleanalytics_enable'] = Configuration::get('googleanalytics_enable');
		 $helper->fields_value['googleanalytics_webtrackingid'] = Configuration::get('googleanalytics_webtrackingid');

		
		 return $helper->generateForm($fields_form);
	


	 }
	 
	  /**
	  * back office module configuration page content
	  */
	 public function getContent()
	 {
		$output = '';
		if (Tools::isSubmit('submit'.$this->name))
		{	
			$error = false;
			$googleanalytics_enable = Tools::getValue('googleanalytics_enable');
			$googleanalytics_webtrackingid = Tools::getValue('googleanalytics_webtrackingid');
						
			 if(!$error)
			 {
				Configuration::updateValue('googleanalytics_enable', $googleanalytics_enable);
				Configuration::updateValue('googleanalytics_webtrackingid', $googleanalytics_webtrackingid);
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
		//verified
		echo "<br><font color=red size=30px>google analytics hook display header</font><br>";
		
		if(Configuration::get('googleanalytics_enable')!="" && Configuration::get('googleanalytics_webtrackingid')!=""){
		
			$this->context->smarty->assign(
				  array(
					  'googleanalytics_webtrackingid' => Configuration::get('googleanalytics_webtrackingid'),
				  )
			); 
			
			return $this->display(__FILE__, 'addGoogleAnalyticsJs.tpl');	
		}
		
	 }

	 
	 
	 /**
	  * hook top to track transactions
	  */
	 public function hookDisplayTop()
	 {
		//verified
		echo "<br><font color=red size=30px>google analytics hook display top</font><br>";

		$controller_name = Tools::getValue('controller');

		if($controller_name=="orderconfirmation"){
			$orderid = $this->context->controller->id_order;
			$order = new Order($orderid);
			$this->context->smarty->assign(
				  array(
					  'orderid' => $orderid,
					  'storename' => $this->context->shop->name,
					  'grandtotal' => $order->total_paid,
					  'shipping' => $order->total_shipping,
					  'tax' => $order->total_paid_tax_incl,
				  )
			); 
			
		}
		return $this->display(__FILE__, 'addTransaction.tpl');	
		
		

	 }
	 
	  /**
	  * hook footer to load JS script for standards actions such as product clicks
	  */
	 public function hookDisplayFooter()
	 {
		//verified
		echo "<br><font color=red size=30px>google analytics hook footer</font><br>";
	 }

	 
	 /**
	  * hook home to display generate the product list associated to home featured, news products and best sellers Modules
	  */
	 public function hookDisplayHome()
	 {	
		//verified
		echo "<br><font color=red size=30px>google analytics hook home</font><br>";

	 }
	 
	 
	 
	 
	 /**
	  * hook product page footer to load JS for product details view
	  */
	 public function hookDisplayFooterProduct()
	 {
		//verified
		echo "<br><font color=red size=30px>google analytics hook product footer</font><br>";

	 }

	 /**
	  * hook shopping cart footer to send the checkout details
	  */
	 public function hookDisplayShoppingCartFooter()
	 {
		//verified
		echo "<br><font color=red size=30px>google analytics hook shopping cart footer</font><br>";

	 }
	 
	
	 /**
	  * hook admin order to send transactions and refunds details
	  */
	 public function hookDisplayAdminOrder()
	 {
		//verified
		echo "<font color=red size=30px>google analytics hook admin order</font>";


	 }

	 

	 

	 





 }
