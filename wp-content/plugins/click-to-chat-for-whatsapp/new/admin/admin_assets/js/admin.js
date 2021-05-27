// Click to Chat
document.addEventListener('DOMContentLoaded', function () {

    // M.AutoInit();

    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems, {});

    var elems = document.querySelectorAll('.collapsible');
    M.Collapsible.init(elems, {});

    var elems = document.querySelectorAll('.modal');
    M.Modal.init(elems, {});

    var elems = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(elems, {});

});

(function ($) {

    // ready
    $(function () {

        $('select').formSelect();
        $('.collapsible').collapsible();
        $('.modal').modal();
        $('.tooltipped').tooltip();
        $('.ht-ctc-color').wpColorPicker();

        // var toast = false;
        // if (typeof M !== "undefined" && M.toast) {
        //     toast = true;
        // }

        // if (toast) {
        //     M.toast({ html: 'Hided on Mobile devices', classes: 'rounded' });
        // }

        // show/hide settings
        function show_hide_options() {

            // default display
            var val = $('.global_display:checked').val();

            $('.global_show_or_hide_label').html('('+val+')');

            if (val == 'show') {
                $(".hide_settings").show();
                $(".show_hide_types .show_btn").attr('disabled', 'disabled');
                $(".show_hide_types .show_box").hide();
            } else if (val == 'hide') {
                $(".show_settings").show();
                $(".show_hide_types .hide_btn").attr('disabled', 'disabled');
                $(".show_hide_types .hide_box").hide();
            }

            // on change
            $(".global_display").on("change", function (e) {

                var change_val = e.target.value;
                $('.global_show_or_hide_label').html('('+change_val+')');

                $(".hide_settings").hide();
                $(".show_settings").hide();
                $(".show_hide_types .show_btn").removeAttr('disabled');
                $(".show_hide_types .hide_btn").removeAttr('disabled');
                $(".show_hide_types .show_box").hide();
                $(".show_hide_types .hide_box").hide();

                if (change_val == 'show') {
                    $(".hide_settings").show(500);
                    $(".show_hide_types .show_btn").attr('disabled', 'disabled');
                    $(".show_hide_types .hide_box").show();
                } else if (change_val == 'hide') {
                    $(".show_settings").show(500);
                    $(".show_hide_types .hide_btn").attr('disabled', 'disabled');
                    $(".show_hide_types .show_box").show();
                }
            });

        }
        show_hide_options();



        // call to action
        var cta_styles = ['.ht_ctc_s2', '.ht_ctc_s3', '.ht_ctc_s3_1', '.ht_ctc_s7'];
        cta_styles.forEach(ht_ctc_admin_cta);

        function ht_ctc_admin_cta(style) {
            // default display
            var val = $(style + ' .select_cta_type').find(":selected").val();
            if (val == 'hide') {
                $(style + " .cta_stick").hide();
            }

            // on change
            $(style + " .select_cta_type").on("change", function (e) {
                var change_val = e.target.value;
                if (change_val == 'hide') {
                    $(style + " .cta_stick").hide(100);
                } else {
                    $(style + " .cta_stick").show(200);
                }
            });
        }

        function ht_ctc_admin_animations() {
            // default display
            var val = $('.select_an_type').find(":selected").val();
            if (val == 'no-animation') {
                $(".an_delay").hide();
                $(".an_itr").hide();
            }

            // on change
            $(".select_an_type").on("change", function (e) {

                var change_val = e.target.value;

                if (change_val == 'no-animation') {
                    $(".an_delay").hide();
                    $(".an_itr").hide();
                } else {
                    $(".an_delay").show(500);
                    $(".an_itr").show(500);
                }
            });
        }
        ht_ctc_admin_animations();

        // on change - styles
        $(".chat_select_style").on("change", function (e) {
            $(".customize_styles_link").animate({ fontSize: '1.2em' }, "slow");
        });

        // Deskop, Mobile - same settings
        function desktop_mobile() {

            // same setting
            if ($('.same_settings').is(':checked')) {
                $(".not_samesettings").hide();
            } else {
                $(".not_samesettings").show();
            }

            $(".same_settings").on("change", function (e) {

                if ($('.same_settings').is(':checked')) {
                    $(".not_samesettings").hide(900);
                } else {
                    $(".not_samesettings").show(900);
                }

            });

        }
        desktop_mobile()

        // WhatsApp number  
        function wn() {

            var cc = $("#whatsapp_cc").val();
            var num = $("#whatsapp_number").val();
            var num2 = num;


            $("#whatsapp_cc").on("change paste keyup", function (e) {
                cc = $("#whatsapp_cc").val();
                call();
            });

            $("#whatsapp_number").on("change paste keyup", function (e) {
                num = $("#whatsapp_number").val();
                num2 = num.replace(/^0+/, '');
                call();
            });

            function call() {
                $(".ht_ctc_wn").html(cc + '' + num);
                $("#ctc_whatsapp_number").val(cc + '' + num2);
            }

        }
        wn();

        // webhook
        function hook() {

            // webhook value - html 
            var hook_value_html = $('.add_hook_value').attr('data-html');

            // add value
            $(document).on('click', '.add_hook_value', function () {

                $('.ctc_hook_value').append(hook_value_html);
            });

            // Remove value
            $('.ctc_hook_value').on('click', '.hook_remove_value', function (e) {
                e.preventDefault();
                $(this).closest('.additional-value').remove();
            });

        }
        hook();

        // things based on screen size
        function ss() {

            var is_mobile = (typeof screen.width !== "undefined" && screen.width > 1024) ? "no" : "yes";

            if ('yes' == is_mobile) {

                // WhatsApp number tooltip position for mobile
                // $("#whatsapp_cc").data('position', 'bottom');
                $("#whatsapp_cc").attr('data-position', 'bottom');
                $("#whatsapp_number").attr('data-position', 'bottom');
            }
        }
        ss();

        function other() {

            // hover text on save_changes button
            var text = $('#ctc_save_changes_hover_text').text();
            $("#submit").attr('title', text);

            // analytics - ga4 display only if ga is enabled.
            $("#google_analytics").on("change", function (e) {
                console.log('change worked');
                if ($('#google_analytics').is(':checked')) {
                    $(".ctc_ga4").show();
                } else {
                    $(".ctc_ga4").hide();
                }
            });

            if ($('#google_analytics').is(':checked')) {
                $(".ctc_ga4").show();
            } else {
                $(".ctc_ga4").hide();
            }

        }
        other();

    });


})(jQuery);
