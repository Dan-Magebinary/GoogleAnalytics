{literal}
<script>
{/literal}



{foreach $ga_products_urls as $ga_product}

$('a[href$="{$ga_product['url']}"]').on("click", function(event) {literal}{{/literal}
	
	ga('ec:addProduct', {literal}{{/literal}
		'id': '{$ga_product["id"]}',
		'name': '{$ga_product["name"]}',
		'category': '{$ga_product["category"]}',
		'brand': '{$ga_product["brand"]}',
		'variant': '{$ga_product["variant"]}',
		'position': '{$ga_product["position"]}',
	{literal}});{/literal}
	
	ga('ec:setAction', 'click', {literal}{list: '{/literal}{$ga_product["list"]}{literal}'});{/literal}
	
	ga('send', 'event', 'Product Click', 'click', '{$ga_product["name"]}' , {literal}{'hitCallback': function() {return !ga.loaded;}} ); {/literal}
	/*ga('send', 'event', 'UX', 'click', 'Results', {
		'hitCallback': function() {
			document.location = '{$ga_product["url"]}';
		}
	});
	return !ga.loaded;*/
{literal}});{/literal}
	

{/foreach}


{assign var="counts" value=0}
{foreach $ga_product_list as $product}
{assign var="producttype" value="typical"}
{if $product->pack eq 1}
{assign var="producttype" value="pack"}
{/if}
{if $product->vitural eq 1}
{assign var="producttype" value="vitural"}
{/if}
{assign var="counts" value=$counts+1}


ga('ec:addImpression', {            
  'id': '{$product['id_product']}',                   
  'name': '{$product['name']}', 
  'type': '{$producttype}', 
  'category': '{$product['category']}',   
  'brand': '{$product['manufacturer_name']}',             
  'variant': '',              
  'list': '{$product['category']}',        
  'position': {$counts},                   
});

{/foreach}








{literal}
</script>
{/literal}



