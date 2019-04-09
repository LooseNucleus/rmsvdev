var ecn = ecn || {};

(function($){
    ecn.ProAdminView = Backbone.View.extend({
        el: '#ecn-admin',

        initialize: function() {
            var self = this;
            self.$('#custom_date_from').datepicker({
                defaultDate: "+1w",
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                numberOfMonths: 3,
                onClose: function(selectedDate) {
                    self.$('#custom_date_to').datepicker("option", "minDate", selectedDate);
                }
            });
            self.$('#custom_date_to').datepicker({
                defaultDate: "+1w",
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                numberOfMonths: 3,
                onClose: function(selectedDate) {
                    self.$('#custom_date_from').datepicker("option", "maxDate", selectedDate);
                }
            });
            self.showCustomDates();
        },

        events: {
            'click .save_results': 'saveResults',
            'click #save_template': 'saveTemplate',
            'change #events_future_in_days': 'showCustomDates',
        },

        showCustomDates: function() {
            if (this.$('#events_future_in_days').val() == 0) {
                this.$('#custom_datepickers').show();
                this.$('#offset_in_days').hide();
                return;
            }
            this.$('#custom_datepickers').hide();
            this.$('#offset_in_days').show();
        },

        saveTemplate: function(event) {
            var self = this;
            $(event.currentTarget).attr('disabled', 'disabled').addClass('disabled');
            self.$('#save_template_message').html('Saving...');
            tinyMCE.triggerSave();
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'ecn_pro_save_template',
                    nonce: $('#wp_ecn_admin_nonce').val(),
                    id: parseInt($(event.currentTarget).attr('data-post-id')),
                    data: $('#ecn-admin form').serialize()
                },
                success: function(msg) {
                    self.$('#save_template_message').html(msg);
                    $(event.currentTarget).removeAttr('disabled').removeClass('disabled');
                    setTimeout(function(){
                        self.$('#save_template_message').html('');
                    }, 5000);
                },
                error: function(v, msg) {
                    alert(msg);
                    $(event.currentTarget).removeAttr('disabled').removeClass('disabled');
                }
            })
        },

        saveResults: function(event) {
            event.preventDefault();
            var self = this;
            if (!self.$('.save_results_title').val()) {
                alert('Enter a title for the template');
                return;
            }
            $(event.currentTarget).attr('disabled', 'disabled').addClass('disabled');
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'ecn_pro_save_results',
                    nonce: $('#wp_ecn_admin_nonce').val(),
                    title: self.$('.save_results_title').val(),
                    data: $('#ecn-admin form').serialize()
                },
                success: function(msg) {
                    self.$('.inside').html(msg);
                },
                error: function(v, msg) {
                    $(event.currentTarget).removeAttr('disabled').removeClass('disabled');
                    alert(msg);
                }
            });
        }
    });

    $(document).ready(function(){
        ecn.proAdminView = new ecn.ProAdminView();
    });
})(jQuery);