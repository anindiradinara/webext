// Click to Chat
(function ($) {

// ready
$(function () {

    // variables
    var url = window.location.href;
    var post_title = (typeof document.title !== "undefined" ) ? document.title : '';
    // is_mobile yes/no,  desktop > 1024 
    var is_mobile = (typeof screen.width !== "undefined" && screen.width > 1024) ? "no" : "yes";
    var no_num = '';

    var ctc = '';
    if ( typeof ht_ctc_chat_var !== "undefined" ) {
        ctc = ht_ctc_chat_var;
        chat_data();
        start();
    } else {
        try {
            if ( document.querySelector('.ht_ctc_chat_data') ) {
                var settings = $('.ht_ctc_chat_data').attr('data-settings');
                ctc = JSON.parse(settings);
            }
        } catch (e) {
            ctc = {};
        }
        chat_data();
        start();
    }

    function chat_data() {

        // if no num
        var chat_data = document.querySelector('.ht_ctc_chat_data');

        if (chat_data) {
            no_num = $(".ht_ctc_chat_data").attr('data-no_number');
            // remove the element
            chat_data.remove();
        }

    }
    
    // start
    function start() {
        
        console.log(ctc);
        $(document).trigger('ht_ctc_ce_settings', [ctc] );

        // fixed position
        ht_ctc();

        // shortcode
        shortcode();

        // custom element
        link();

    }


    



    // fixed position
    function ht_ctc() {
        console.log('ht_ctc');
        var ht_ctc_chat = document.querySelector('.ht-ctc-chat');
        if (ht_ctc_chat) {
            
            $(document).trigger('ht_ctc_ce_chat');

            // display
            display_settings(ht_ctc_chat);

            // click
            ht_ctc_chat.addEventListener('click', function () {
                // link
                ht_ctc_link(ht_ctc_chat);
            });

        }
    }

    // display settings - Fixed position style
    function display_settings(ht_ctc_chat) {

        if ('yes' == ctc.schedule) {
            console.log('scheduled');
            $(document).trigger('ht_ctc_ce_display', [ctc, display_chat, ht_ctc_chat ]);
        } else {
            console.log('display directly');
            display_chat(ht_ctc_chat);
        }

    }
    
    // display based on device
    function display_chat(p) {
        
        if (is_mobile == 'yes') {
            if ( 'show' == ctc.dis_m ) {

                // remove desktop style
                var rm = document.querySelector('.ht_ctc_desktop_chat');
                (rm) ? rm.remove() : '';

                p.style.cssText = ctc.pos_m + ctc.css;
                display(p)
            }
        } else {
            if ( 'show' == ctc.dis_d ) {

                // remove mobile style
                var rm = document.querySelector('.ht_ctc_mobile_chat');
                (rm) ? rm.remove() : '';

                p.style.cssText = ctc.pos_d + ctc.css;
                display(p)
            }
        }
    }

    function display(p) {
        // p.style.removeProperty('display');
        // var x = p.style.getPropertyValue("display");
        // p.style.display = "block";
        try {
            $(p).show(parseInt(ctc.se));
            console.log(dt);
        } catch (e) {
            p.style.display = "block";
        }

        ht_ctc_things(p);
    }

    // animiation, cta hover effect
    function ht_ctc_things(p) {
        console.log('animations '+ ctc.ani);
        // animations
        setTimeout(function () {
            p.classList.add('ht_ctc_animation', ctc.ani);
        }, 120);

        // cta hover effects
        $(".ht-ctc-chat").hover(function () {
            $('.ht-ctc-chat .ht-ctc-cta-hover').show(120);
        }, function () {
            $('.ht-ctc-chat .ht-ctc-cta-hover').hide(100);
        });
    }

    // analytics
    function ht_ctc_chat_analytics(values) {

        console.log('analytics');

        $(document).trigger('ht_ctc_analytics');

        // global number (fixed, user created elememt)
        var id = ctc.number;

        // if its shortcode
        if (values.classList.contains('ht-ctc-sc')) {
            // shortcode number
            id = values.getAttribute('data-number');
        }

        // Google Analytics
        var ga_category = 'Click to Chat for WhatsApp';
        var ga_action = 'chat: ' + id;
        var ga_label = post_title + ', ' + url;

        // if ga_enabled
        if (ctc.ga || ctc.ga4) {
            console.log('google analytics');
            
            if (typeof gtag !== "undefined") {
                console.log('gtag');
                if (ctc.ga4) {
                    // ga4
                    // gtag may not work if ga4 installed using gtm
                    console.log('ga4');
                    gtag('event', 'click to chat', {
                        'number': id,
                        'title': post_title,
                        'url': url,
                    });
                } else {
                    gtag('event', ga_action, {
                        'event_category': ga_category,
                        'event_label': ga_label,
                    });
                }
            } else if (typeof ga !== "undefined" && typeof ga.getAll !== "undefined") {
                console.log('ga');
                var tracker = ga.getAll();
                tracker[0].send("event", ga_category, ga_action, ga_label);
                // ga('send', 'event', 'check ga_category', 'ga_action', 'ga_label');
                // ga.getAll()[0].send("event", 'check ga_category', 'ga_action', 'ga_label');
            } else if (typeof __gaTracker !== "undefined") {
                __gaTracker('send', 'event', ga_category, ga_action, ga_label);
            }
        }

        // dataLayer
        if (typeof dataLayer !== "undefined") {
            console.log('dataLayer');
            dataLayer.push({
                'event': 'Click to Chat',
                'type': 'chat',
                'number': id,
                'title': post_title,
                'url': url,
                'event_category': ga_category,
                'event_label': ga_label,
                'event_action': ga_action
            });
        }

        // google ads - call conversation code
        if (ctc.ads) {
            console.log('google ads enabled');
            if (typeof gtag_report_conversion !== "undefined") {
                console.log('calling gtag_report_conversion');
                gtag_report_conversion();
            }
        }

        // FB Pixel
        if (ctc.fb) {
            console.log('fb pixel');
            if (typeof fbq !== "undefined") {
                fbq('trackCustom', 'Click to Chat by HoliThemes', {
                    'Category': 'Click to Chat for WhatsApp',
                    'return_type': 'chat',
                    'ID': id,
                    'Title': post_title,
                    'URL': url
                });
            }
        }

    }

    // link - chat
    function ht_ctc_link(values) {

        console.log(ctc.number);
        $(document).trigger('ht_ctc_ce_number', [ctc]);
        console.log(ctc.number);

        var number = ctc.number;
        var pre_filled = ctc.pre_filled;
        pre_filled = pre_filled.replace(/\[url]/gi, url);
        pre_filled = encodeURIComponent(pre_filled);

        if ('' == number) {
            console.log('no number');
            $(".ht-ctc-chat").html(no_num);
            return;
        }

        // web/api.whatsapp or wa.me 
        if (ctc.web && is_mobile !== 'yes') {
            // web.whatsapp - if web api is enabled and is not mobile
            window.open('https://web.whatsapp.com/send' + '?phone=' + number + '&text=' + pre_filled, '_blank', 'noopener');
        } else {
            // wa.me
            window.open('https://wa.me/' + number + '?text=' + pre_filled, '_blank', 'noopener');
        }

        // analytics
        ht_ctc_chat_analytics(values);
        
        // hook
        hook(number);

    }

    // shortcode
    function shortcode() {
        // shortcode - click
        $(document).on('click', '.ht-ctc-sc-chat', function () {

            var number = this.getAttribute('data-number');
            var pre_filled = this.getAttribute('data-pre_filled');
            pre_filled = pre_filled.replace(/\[url]/gi, url);
            pre_filled = encodeURIComponent(pre_filled);

            if (ctc.web && is_mobile !== 'yes') {
                // web.whatsapp - if web api is enabled and is not mobile
                window.open('https://web.whatsapp.com/send' + '?phone=' + number + '&text=' + pre_filled, '_blank', 'noopener');
            } else {
                // wa.me
                window.open('https://wa.me/' + number + '?text=' + pre_filled, '_blank', 'noopener');
            }

            // analytics
            ht_ctc_chat_analytics(this);

            // hook
            hook(number);
        });
    }

    // custom element
    function link() {

        $(document).on('click', '.ctc_chat, #ctc_chat', function () {
            console.log('class/Id: ctc_chat');
            ht_ctc_link(this);
        });

        $(document).on('click', '[href="#ctc_chat"]', function (e) {
            console.log('#ctc_chat');
            e.preventDefault();
            ht_ctc_link(this);
        });
    }


    function hook(number) {

        console.log('hook');

        if (ctc.hook_url) {

            var h_url = ctc.hook_url;

            console.log(h_url);

            var hook_values = {};

            // hook values
            if (ctc.hook_v) {
                
                var hook_values = ctc.hook_v;
            
                console.log(typeof hook_values);
                console.log(hook_values);

            }

            console.log(h_url);
            console.log(hook_values);

            $(document).trigger('ht_ctc_ce_hook', [ctc, number]);

            h_url = ctc.hook_url;
            console.log(h_url);
            
            hook_values = ctc.hook_v;
            console.log(hook_values);

            data = JSON.stringify(hook_values);

            console.log(data);
            console.log(typeof data);


            $.ajax({
                url: h_url,
                type: "POST",
                mode: 'no-cors',
                data: data,
                success: function (response) {
                    console.log(response);
                }
            });

            // fetch(h_url, {
            //     method: 'POST',
            //     mode: 'no-cors',
            //     headers: {
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify(data),
            // }).then(response => {
            //     console.log(response);
            // }).catch(e => {
            //     console.log(e.message);
            // });

        }

        
    }


});

})(jQuery);