<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class Link extends LinkCore
{

	/**
	 * Create a link to a product
	 *
	 * @param mixed $product Product object (can be an ID product, but deprecated)
	 * @param string $alias
	 * @param string $category
	 * @param string $ean13
	 * @param int $id_lang
	 * @param int $id_shop (since 1.5.0) ID shop need to be used when we generate a product link for a product in a cart
	 * @param int $ipa ID product attribute
	 * @return string
	 */
	public function getProductLink($product, $alias = null, $category = null, $ean13 = null, $id_lang = null, $id_shop = null, $ipa = 0, $force_routes = false)
	{
		$url = parent::getProductLink($product, $alias, $category, $ean13, $id_lang, $id_shop, $ipa, $force_routes);
		
		if (!is_object($product))
		{
			if (is_array($product) && isset($product['id_product']))
				$product = new Product($product['id_product'], false, $id_lang, $id_shop);
			elseif ((int)$product)
				$product = new Product((int)$product, false, $id_lang, $id_shop);
			else
				throw new PrestaShopException('Invalid product vars');
		}
		
		$category = new Category($product->id_category_default);
		$manufactory = Manufacturer::getNameById((int)$product->id_manufacturer);
		
		$ga_product = array(
			'url'=>$url,
			'id'=>$product->id,
			'name'=>Product::getProductName($product->id),
			'category'=>$category->getName(),
			'brand'=>$manufactory,
			'variant'=>$product->price,
			'position'=>0,
			'list'=>Tools::getValue('controller'),
		);
			
		$context = Context::getContext();
		$ga_products_urls = $context->smarty->getTemplateVars('ga_products_urls');
	
		if(!is_null($ga_products_urls)){
			$ga_product['position'] = count($ga_products_urls)+1;
			$ga_products_urls[] = $ga_product;
			$context->smarty->assign('ga_products_urls',$ga_products_urls);
		}else{
			$ga_product['position'] = 1;
			$context->smarty->assign('ga_products_urls' , array($ga_product));
		}
		return $url;
		
		
	}

	
}

