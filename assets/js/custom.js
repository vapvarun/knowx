/**
 * File custom.js.
 *
 * Theme Custom enhancements for a better user experience.
 *
 */
// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;
(function($, window, document, undefined) {
    'use strict';

    var HELPDESK = window.HELPDESK || {};


    // Site Loader
    HELPDESK.siteLoader = function() {
        $('.site-loader').addClass('loaded');
    };

    // Header Height
    HELPDESK.headerHeight = function() {
        var header_height = $('.site-header-wrapper').height();
        $('.site').css("paddingTop", header_height + 12 + "px");
        $('.page-template-full-width:not(.elementor-editor-active) .site').css("paddingTop", header_height - 10 + "px");
        $('#cover-image-container').css("marginTop", -header_height + 46 + "px");

        var $document = $(document),
            $elementHeader = $('.site-header-wrapper'),
            className = 'has-sticky-header';

        $document.scroll(function() {
            $elementHeader.toggleClass(className, $document.scrollTop() >= 1);
        });
    };

    // Header Search
    HELPDESK.headerSearch = function() {

        $('.search-icon').on('click', function(e) {
            e.preventDefault();
            $('.site-header .top-menu-search-container').toggle();
        });

        $(document).mouseup(function(e) {
            var container = $(".top-menu-search-container");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut();
            }
        });

        $("#primary-menu a, .cart a.menu-icons-wrapper, .bp-icon-wrap, a.user-link, .site-sub-header a, .site-wrapper a").focusin(function() {
            $('.site-header .top-menu-search-container').hide();
        });

    };

    // Desktop Menu Toggle
    HELPDESK.desktopMenuToggle = function() {
        if ($(window).width() > 768) {
            $('.primary-menu-container #primary-menu').superfish({
                delay: 600,
                animation: {
                    opacity: 'show'
                },
                animationOut: {
                    opacity: 'hide'
                },
                speed: 'fast',
                speedOut: 'fast',
                cssArrows: false,
                disableHI: false,
            });
        }

        $(".helpdesk-mobile-menu").focusout(function() {
            $('.mobile-menu-heading .close-menu').focusin();
        });

    };

    // Mobile Menu Toggle
    HELPDESK.mobileNav = function() {
        var widget = $('.menu-toggle'),

            body = $('body');
        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            } else {
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();

            } else {
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        body.on("click touchstart", ".mobile-menu-close", function() {
            if (isOpened()) {
                closeWidget();
            }
        });

        body.on("click", ".menu-close", function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            }
        });

        $(document).keyup(function(e) {
            if (e.keyCode === 27 && isOpened())
                closeWidget();
        });

        var closeWidget = function() {
            $('body').removeClass('mobile-menu-opened');
            $(widget).removeClass('menu-toggle-open');
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
            $(widget).addClass('menu-toggle-open');
        };

        var isOpened = function() {
            return $('body').hasClass('mobile-menu-opened');
        };

    };

    // Blog Layout
    HELPDESK.blogLayout = function() {

        $('.grid-layout').isotope({
            // options
            itemSelector: '.entry-layout',
            layoutMode: 'fitRows'
        });

        $('.masonry-layout').isotope({
            itemSelector: '.entry-layout',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.grid-sizer',
            }
        })
    };

    // fitVids
    HELPDESK.fitVids = function() {

        var doFitVids = function() {
            setTimeout(function() {
                $('iframe[src*="youtube"], iframe[src*="vimeo"]').parent().fitVids();
            }, 300);
        };
        doFitVids();
        $(document).ajaxComplete(doFitVids);

        var doFitVidsOnLazyLoad = function(event, data) {
            if (typeof data !== 'undefined' && typeof data.element !== 'undefined') {
                //load iframe in correct dimension
                if (data.element.getAttribute('data-lazy-type') == 'iframe') {
                    doFitVids();
                }
            }
        };
        $(document).on('helpdesk_lazy_load', doFitVidsOnLazyLoad);

    };

    // stickySidebar
    HELPDESK.stickySidebar = function() {

        var headerHeight = $('.site-header-wrapper').height();
        var headerHeightExt = headerHeight + 54;
        $('.sticky-sidebar-enable .sticky-sidebar').stick_in_parent({
            offset_top: headerHeightExt,
            recalc_every: 1,
        });

    };

    // tableDataAtt
    HELPDESK.tableDataAtt = function() {

        if ($('table').length) {
            var $th = $("thead th");
            $('tbody tr td').attr('data-attr', function() {
                return $th.eq($(this).index()).text();
            });
        }
    };

    $(document).ready(function() {

        HELPDESK.headerHeight();
        HELPDESK.headerSearch();
        HELPDESK.desktopMenuToggle();
        HELPDESK.mobileNav();
        HELPDESK.fitVids();
        HELPDESK.tableDataAtt();

    });

    $(window).resize(function() {
        // do stuff
        HELPDESK.headerHeight();
    });

    $(window).scroll(function() {
        // do stuff
    });

    $(window).load(function() {
        HELPDESK.siteLoader();
        HELPDESK.stickySidebar();
        HELPDESK.blogLayout();
    });

})(jQuery, window, document);