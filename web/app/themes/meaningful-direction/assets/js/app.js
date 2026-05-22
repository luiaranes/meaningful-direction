'use strict';

/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If your body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function ($) {
    function mdInitSiteNav() {
        const mq =
            typeof window.matchMedia === 'function' ? window.matchMedia('(min-width: 881px)') : null;
        const $nav = $('.md-site-nav');

        if (!$nav.length) {
            return;
        }

        const $mobToggle = $('.md-nav-mobile-toggle');
        const $mobPanel = $('#md-primary-mobile-panel');
        let closeCareTimer = null;

        function desktop() {
            return !!(mq && mq.matches);
        }

        function clearCareTimer() {
            if (closeCareTimer !== null) {
                window.clearTimeout(closeCareTimer);
                closeCareTimer = null;
            }
        }

        function closeCareAll() {
            clearCareTimer();
            $nav.find('.nav-dropdown').removeClass('is-care-open');
            $nav.find('.md-dropdown-panel').attr('aria-hidden', 'true');
            $nav.find('[data-md-dropdown-trigger]').attr('aria-expanded', 'false');
        }

        function closeMobileNav() {
            if (!$mobPanel.length) {
                return;
            }
            $nav.removeClass('is-mobile-open');
            $mobToggle.attr('aria-expanded', 'false');
            $mobPanel.prop('hidden', true).attr('aria-hidden', 'true');
            closeCareAll();
        }

        function onBreakpointChange() {
            closeCareAll();
            if (desktop()) {
                closeMobileNav();
            }
        }

        $(window).on('scroll.mdSiteNav', function () {
            $nav.toggleClass('scrolled', window.scrollY > 8);
        });

        window.requestAnimationFrame(function () {
            $nav.removeClass('is-initial-mount');
        });

        $mobToggle.on('click.mdSiteNav', function () {
            let open = !$nav.hasClass('is-mobile-open');

            closeCareAll();

            $nav.toggleClass('is-mobile-open', open);

            $(this).attr('aria-expanded', open ? 'true' : 'false');

            $mobPanel.prop('hidden', !open);

            $mobPanel.attr('aria-hidden', open ? 'false' : 'true');
        });

        $mobPanel.on('click.mdSiteNav', 'a', function () {
            closeMobileNav();
        });

        $nav.find('[data-md-dropdown]').each(function () {
            const $dropdown = $(this);
            const $trigger = $dropdown.find('[data-md-dropdown-trigger]');
            const $panel = $dropdown.find('.md-dropdown-panel');

            $dropdown.on('mouseenter.mdSiteNav', function () {
                if (!desktop()) {
                    return;
                }

                clearCareTimer();
                $dropdown.siblings('.nav-dropdown').removeClass('is-care-open');

                $('.md-site-nav').find('.nav-dropdown').not($dropdown).each(function () {
                    const other = $(this);

                    other.removeClass('is-care-open');
                    other.find('.md-dropdown-panel').attr('aria-hidden', 'true');
                    other.find('[data-md-dropdown-trigger]').attr('aria-expanded', 'false');
                });

                $dropdown.addClass('is-care-open');

                $trigger.attr('aria-expanded', 'true');

                $panel.attr('aria-hidden', 'false');
            });

            $dropdown.on('mouseleave.mdSiteNav', function () {
                if (!desktop()) {
                    return;
                }

                clearCareTimer();

                closeCareTimer = window.setTimeout(function () {
                    $dropdown.removeClass('is-care-open');
                    $trigger.attr('aria-expanded', 'false');
                    $panel.attr('aria-hidden', 'true');
                }, 180);
            });
        });

        $('body').on('keydown.mdSiteNav', function (evt) {
            if (evt.keyCode !== 27) {
                return;
            }
            closeCareAll();
            if ($nav.hasClass('is-mobile-open')) {
                closeMobileNav();
            }
        });

        $(document).on('mousedown.mdSiteNav touchstart.mdSiteNav', function (evt) {
            const $t = $(evt.target);

            if ($nav.hasClass('is-mobile-open') && !$t.closest('.md-site-nav').length) {
                closeMobileNav();
            }

            if (desktop() && !$t.closest('.nav-dropdown').length) {
                closeCareAll();
            }
        });

        if (mq && mq.addEventListener) {
            mq.addEventListener('change', onBreakpointChange);
        }
    }

    function mdInitProcessTabs() {
        const $root = $('.md-site-process');

        if (!$root.length) {
            return;
        }

        $root.on('click', '[data-md-process-tab]', function () {
            const idx = parseInt($(this).data('md-process-tab'), 10);

            if (isNaN(idx)) {
                return;
            }

            $root.find('[data-md-process-tab]').each(function () {
                const $t = $(this);
                const on = parseInt($t.data('md-process-tab'), 10) === idx;

                $t.toggleClass('active', on);
                $t.attr('aria-selected', on ? 'true' : 'false');
            });
            $root.find('[data-md-process-panel]').each(function () {
                const $p = $(this);
                const on = parseInt($p.data('md-process-panel'), 10) === idx;

                if (on) {
                    $p.removeAttr('hidden');
                } else {
                    $p.attr('hidden', 'hidden');
                }
            });
        });
    }

    function mdInitStories() {
        const $root = $('.md-site-stories');

        if (!$root.length) {
            return;
        }

        const $grid = $root.find('[data-md-stories]');
        const total = parseInt($grid.data('md-stories-total'), 10) || 0;

        if (total < 1) {
            return;
        }

        let i = 0;
        const $prev = $grid.find('[data-md-story-prev]');
        const $next = $grid.find('[data-md-story-next]');
        const $counter = $grid.find('.md-stories-counter');

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function show(idx) {
            i = ((idx % total) + total) % total;
            $grid.find('[data-md-story]').each(function () {
                const $el = $(this);
                const si = parseInt($el.data('md-story'), 10);

                if (isNaN(si)) {
                    return;
                }

                const on = si === i;

                if (on) {
                    $el.removeAttr('hidden');
                } else {
                    $el.attr('hidden', 'hidden');
                }
            });
            $counter.text(`${pad(i + 1)} / ${pad(total)}`);
            const panelId = `md-story-panel-${i}`;

            $prev.attr('aria-controls', panelId);
            $next.attr('aria-controls', panelId);
            const dis = total < 2;

            $prev.prop('disabled', dis);
            $next.prop('disabled', dis);
        }

        $prev.on('click', function () {
            show(i - 1);
        });
        $next.on('click', function () {
            show(i + 1);
        });
        show(0);
    }

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.
    const wh = {
        // All pages
        common: {
            init: function () {
                mdInitSiteNav();
                mdInitProcessTabs();
                mdInitStories();

                // JavaScript to be fired on the all page
            },
            finalize: function () {
                // JavaScript to be fired on the all page, after the init JS
            },
        },
        // Home page
        home: {
            init: function () {
                // JavaScript to be fired on the home page
            },
            finalize: function () {
                // JavaScript to be fired on the home page, after the init JS
            },
        },
    };

    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    const UTIL = {
        fire: function (func, funcname, args) {
            const namespace = wh;
            funcname = funcname === undefined ? 'init' : funcname;
            const fire = func !== '';
            const fire1 = fire && namespace[func];
            const fire2 =
                fire1 && typeof namespace[func][funcname] === 'function';

            if (fire2) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(
                document.body.className.replace(/-/g, '_').split(/\s+/),
                function (i, classnm) {
                    UTIL.fire(classnm);
                    UTIL.fire(classnm, 'finalize');
                }
            );

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        },
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);
})(jQuery); // Fully reference jQuery after this point.
