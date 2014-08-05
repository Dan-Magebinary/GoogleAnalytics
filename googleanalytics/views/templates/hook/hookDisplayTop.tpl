{literal}<script>
ga('ecommerce:addTransaction', {
  'id': '{/literal}{$orderid}{literal}',                     // Transaction ID. Required.
  'affiliation': '{/literal}{$storename}{literal}',   // Affiliation or store name.
  'revenue': '{/literal}{$grandtotal}{literal}',               // Grand Total.
  'shipping': '{/literal}{$shipping}{literal}',                  // Shipping.
  'tax': '{/literal}{$tax}{literal}'                     // Tax.
});

</script>
{/literal}