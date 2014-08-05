{literal}<script>
ga('ec:addProduct', {{/literal}
  'id': '{$ga_product->id}',                   
  'name': '{$name}',   
  'category': '{$category->name}',              
  'brand': '{$ga_product->manufacturer_name}',                
  'variant': '{$ga_product->price}',               
{literal}});
ga('ec:setAction', 'detail');
ga('send', 'pageview');  
</script>{/literal}


