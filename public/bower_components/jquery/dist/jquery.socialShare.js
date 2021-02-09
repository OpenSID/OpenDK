/*
 *  Project: jQuery simple social sharing
 *  Description: Tweet / post to FB using proper modals
 *  Author: Neil Carpenter
 */

;(function ($, window, document, undefined) {

    var pluginName = 'socialSharers';
    var defaults = {
        twitter: {
            handle: null
        },
        facebook: {
            appID: null
        },
        googleplus: {
            // NA
        }
    };

    var width, height, pos, url, link, title;

    function SocialSharers(element, options) {

        this.element = $( element );
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();

    }

    /**
     * Return a new, centered popup based on the
     * specified width and height.
     *
     */
    function newPosition(width, height) {

        var position = {};

        // parseInt incase we're dealing with strings
        width = parseInt(width, 10) || 500;
        height = parseInt(height, 10) || 500;

        position.left = ( screen.width / 2 ) - ( width / 2 );
        position.top = ( screen.height / 2 ) - ( height / 2 );

        return position;

    }

    SocialSharers.prototype = {
        init: function () {

            this.loadScripts();
            this.events();

        },
        loadScripts: function () {

            // asychroniously loads external scripts
            // From https://gist.github.com/necolas/1025811
            var js,
                fjs = document.getElementsByTagName('script')[0],
                add = function(url, id) {
                    if (document.getElementById(id)) {return;}
                    js = document.createElement('script');
                    js.src = url;
                    id && (js.id = id);
                    fjs.parentNode.insertBefore(js, fjs);
                };

            // Facebook SDK
            if ( this.options.facebook.appID && this.element.find('[data-share="facebook"]').length ) add('//connect.facebook.net/en_US/all.js', 'facebook-jssdk');

        },
        facebook: function( $el ) {

            link = $el.data('link') || window.location.href;

            if ( this.options.facebook.appID ) {

                var obj = {
                    method: 'feed',
                    link: link,
                    picture: $el.data('image') || '',
                    name: $el.data('title') || document.title,
                    description: $el.data('description') || ''
                };

                FB.ui(obj);

            } else {

                width = 640;
                height = 320;
                pos = newPosition(width, height);

                url = 'https://www.facebook.com/sharer/sharer.php?u=';
                url += encodeURIComponent( link );

                window.open(url, 'fbshare', 'width='+width+', height='+height+', top='+pos.top+', left='+pos.left+', menubar=no, status=no, toolbar=no, ');

            }

        },
        twitter: function( $el ) {

            var via, hashtags;

            link = ( $el.data('link') ) ? $el.data('link') : window.location.href;
            title = ( $el.data('title') ) ? $el.data('title') : document.title;

            via = ( this.options.twitter.handle ) ? '&via=' + this.options.twitter.handle : '';
            hashtags = ( $el.data('hashtags') ) ? '&hashtags=' + $el.data('hashtags') : '';

            width = 550;
            height = 450;
            pos = newPosition(width, height);

            url = 'https://twitter.com/share?url=' + encodeURIComponent( link ) +
                '&text=' + encodeURIComponent( title )  + via + hashtags;

            window.open(url, 'twshare', 'width='+width+', height='+height+', top='+pos.top+', left='+pos.left+', menubar=no, status=no, toolbar=no, ');

        },
        googleplus: function ( $el ) {

            link = ( $el.data('link') ) ? $el.data('link') : window.location.href;
            title = ( $el.data('title') ) ? $el.data('title') : document.title;

            width = 600;
            height = 540;
            pos = newPosition(width, height);

            url = 'https://plus.google.com/share?url=' + encodeURIComponent( link );

            window.open(url, 'gpshare', 'width='+width+', height='+height+', top='+pos.top+', left='+pos.left+', menubar=no, status=no, toolbar=no, ');

        },
        events: function() {

            var self = this;

            this.element.on('click', 'a', function( e ) {

                e.preventDefault();

                var $el = $(e.target);

                switch ( $el.data('share') ) {
                    case 'facebook': self.facebook( $el ); break;
                    case 'twitter': self.twitter( $el ); break;
                    case 'googleplus': self.googleplus( $el ); break;
                }

                $el.blur();

            });

            if ( this.options.facebook.appID ) {

                window.fbAsyncInit = function() {
                    FB.init({appId: self.options.facebook.appID, status: false});
                };

            }

        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new SocialSharers(this, options));
            }
        });
    };

})(jQuery, window, document);
