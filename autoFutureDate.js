jQuery(document).ready(function() {
   jQuery('.edit-timestamp').after(' <a href="" id="afd-link">Auto</a>');
   jQuery('#afd-link').click(function() {
        var data = {
            action: 'afd_get_date',
            post_id: 1
        }
        jQuery.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function(r) {
                date = new Date(r);
                jQuery('#aa').val(date.getFullYear());
                jQuery('#jj').val(date.getDate());
                jQuery('#hh').val(date.getHours());
                jQuery('#mn').val((date.getMinutes() < 10 ? '0' : '') + date.getMinutes());
                jQuery('#mm').val((date.getMonth() + 1 < 10 ? '0' : '') + (date.getMonth() + 1));
                jQuery('.save-timestamp').click();

            },
            error: function(r) {
                alert('Could not get date'); 
            }

        }
        );
        return false;
   });


   // Edit page
   if (jQuery('#afd_minTime, #afd_maxTime').length == 2) {
       jQuery('#afd_minTime').blur(function() {
          jQuery('#afd_minFormatted').html(afd_formatDate(jQuery(this).val()));
       });
       jQuery('#afd_maxTime').blur(function() {
          jQuery('#afd_maxFormatted').html(afd_formatDate(jQuery(this).val()));
       });
       jQuery('#afd_minFormatted').html(afd_formatDate(jQuery('#afd_minTime').val()));
       jQuery('#afd_maxFormatted').html(afd_formatDate(jQuery('#afd_maxTime').val()));
   }
});

function afd_formatDate(str) {
   var ray = str.split(/:|\./);
   var ret = Array();
   // Need to make sure the array is exactly 3 length
   if (ray.length > 3) ray = ray.slice(ray.length - 3);
   while (ray.length < 3) ray.unshift(0)

   // Convert 'em all to ints
   for (i=0; i<ray.length; ++i) ray[i] = parseInt(ray[i]);

   if (ray[0] > 0) ret[ret.length] = ray[0] + ' day' + ((ray[0] > 1) ? 's': '');
   if (ray[1] > 0) ret[ret.length] = ray[1] + ' hour' + ((ray[1] > 1) ? 's': '');
   if (ray[2] > 0) ret[ret.length] = ray[2] + ' minute' + ((ray[2] > 1) ? 's': ''); 

   return ret.join(', ');
}