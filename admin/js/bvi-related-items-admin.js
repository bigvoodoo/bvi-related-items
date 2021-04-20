(function( $ ) {
	'use strict';

	jQuery(function($) {
		// when the add relationship button is clicked add that rel item
		$('input#add_relationship').click(function() {
				var select = $('#related-items-select'),
				container = $('#related-items-box'),
				id = select.val(),
				title = $('#related-items-select :selected').text();
	
				if($('#related_item_title').val().length > 0) {
					var title = $('#related_item_title').val();
				}
			
			if ($('#related-item-' + id).length == 0 && title !== "Select") {
				container.append('<div class="related-item" id="related-item-' + id + '" style="font-size:11px;margin:6px 6px 8px;padding:8px;background:#f1f1f1;border:1px solid #dfdfdf;cursor:move;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;"><img src="'+wpurl+'/wp-content/plugins/related-items/images/bullet_red.png" title="Remember to save this post when you are finished making changes" style="float:left;margin-top:-8px;margin-left:-8px;"><input type="hidden" name="related-items[]" value="' + id + '"><input type="hidden" name="related-items-titles[]" value="' + title + '"><span class="related-item-title">' + title + '</span><a href="#" title="Remove from list"><img src="'+wpurl+'/wp-content/plugins/related-items/images/delete.png"></a></div>');
			}
		});
	
		// when the filter select changes, update the related item select list  
		$('#related-items-category-filter-select').change(function(){
				var select = $(this);		
					title = this.options[this.options.selectedIndex].value;
				if(title == "all") {
					$("select#related-items-select option").show();
				} else {
				$("select#related-items-select option").hide();
					var css_title = "." + title;
				
					$("select#related-items-select option").filter(css_title).show();
				}
		});
		
		// delete relationship record
		$('.related-item a').click(function() {
			var item_div = $(this).parent();
			
			item_div.css('background-color', '#ff0000').fadeOut('normal', function() {
				item_div.remove();
			});
			return false;
		});
		
		$('#related-items-box').sortable();	
	});

})( jQuery );
