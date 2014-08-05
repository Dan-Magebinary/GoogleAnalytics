{assign var="counts" value=0}
{foreach $ga_homefeatured_product_list as $product}
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
  'list': 'featured product list',        
  'position': {$counts},                   
});

{/foreach}

{assign var="counts" value=0}
{foreach $ga_homenew_product_list as $product}
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
  'list': 'new product list',        
  'position': {$counts},                   
});

{/foreach}


{assign var="counts" value=0}
{foreach $ga_homebestsell_product_list as $product}
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
  'list': 'best sell products',        
  'position': {$counts},                   
});

{/foreach}

