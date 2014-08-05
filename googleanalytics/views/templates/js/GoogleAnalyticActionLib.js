var GoogleAnalyticEnhancedECommerce = {
		
		
		
		setCurrency: function(ShortCut,Currency) {
		
			ga('set', ShortCut, Currency);
		
		},
		
		addProducts: function(Product) {
			
		var Attrs = {};
			
		for (i = 0; i < Product.Id.length; i++) {
			
			for(var key in Product) {
			
				console.log(key);
				//Attrs.push(key);
			
			}

			/**
			Attrs = {
					'id': Product.Id[i], 				// Product ID (string).
					'name': Product.Name[i], 			// Product name (string).
					'category': Product.Category[i], 	// Product category (string).	
					'brand': Product.Brand[i],			// Product brand (string).
					'variant': Product.Variant[i],		// Product variant (string). PS: Could be colors
					'price': Product.Price[i],			// Product price (currency).
					'quantity': Product.Qty[i],			// Product quantity (number).			
					//'coupon': Product.Coupon[i]			// Product price (currency).
			};
			**/
			
			console.log(Attrs);
			return ga('ec:addProduct', Attrs);
			
			}
		},
		
		
		addToCart: function(Product) {
		
			 this.addProducts(Product);
			  
			  ga('ec:setAction', 'add');
			  ga('send', 'event', 'UX', 'click', 'Add to Cart');     // Send data using an event.
		
		},
		
		removeFromCart: function(Product) {
		
			 this.addProducts(Product);

			  ga('ec:setAction', 'remove');
			  ga('send', 'event', 'UX', 'click', 'add to cart');     // Send data using an event.
		
		},
		
		/***
		Measuring a product view
		- Includes a JavaScript code on the product list page (category and sub-category) to send 
		the product's details:
		o Product(s): id, name, type, category, brand, variant, list and position in the list.4
		- The “others” listing (home featured products, best sellers and news products) will 
		display the details by re-implementing the way to get the products list from the 
		Module into the Google Analytics Module.
		***/
		
		
		addProductImpression: function(Product) {
		
			ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
				'id': Product.Id,                   // Product ID (string).
				'name': Product.Name, // Product name (string).
				'category': Product.Category,   // Product category (string).
				'brand': Product.Brand,                // Product brand (string).
				'variant': Product.Variant,               // Product variant (string) eg Black.
				'list': Product.List,         // Product list (string) eg Search Results.
				'position': Product.Position,                    // Product position (number).
				'dimension1': Product.Dimension1            // Custom dimension (string).
			});
			ga('send', 'pageview'); 
		},
		
		/***
		Measuring refunds
		- Includes a JavaScript code on the order details page (back-office) to send the refund's 
		details:
		o Order: id, type, affiliation, revenue, tax, shipping and coupon.
		Note: Some orders are partially refunded, the details will only be sent when the merchant uses
		the standard refund function.
		****/
		
		// Refund an entire transaction.
		refundByOrderId: function(Order) {
			//this.AddProducts(Order)
			ga('ec:setAction', 'refund', {
				'id': Order.Id    // Transaction ID is only required field for full refund.
			});
		},
			
		// Refund a single product.
		refundByProduct: function(Order) {

			this.AddProducts(Product);

			ga('ec:setAction', 'refund', {
			  'id': Order.Id,       // Transaction ID is required for partial refund.
			});
	
		},
		
		addProductClick: function(Order) {
		 ga('ec:setAction', 'click', {list: 'Search Results'});

		  // Send click with an event, then send user to product page.
		 ga('send', 'event', 'UX', 'click', 'Results', {
			  'hitCallback': function() {
				document.location = Order.Url;
			  }
		  });
		}
		
		













}


var Product = {
			Id: "100",
			Name: "Product Name",
			Category: "Product Cate",
			Brand: "Product Brand",
			Variant: "Product Color",
			Price: "100",
			Qty: "1"
}




	