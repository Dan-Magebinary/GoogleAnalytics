{literal}
<script>
{/literal}


// Refund an entire transaction.
ga('ec:setAction', 'refund', {
  'id': 'T12345'    // Transaction ID is only required field for full refund.
});


//Refund a single product.
ga('ec:addProduct', {
  'id': 'P12345',       // Product ID is required for partial refund.
  'quantity': 1         // Quantity is required for partial refund.
});

ga('ec:setAction', 'refund', {
  'id': 'T12345',       // Transaction ID is required for partial refund.
});
ga('send');

{literal}
</script>
{/literal}
