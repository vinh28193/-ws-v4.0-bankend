<?php
/**
 * @var \common\models\weshop\Website $website
 */

use Yii;

if ($website->isWSID()) {
    ?>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '805525609495843');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=805525609495843&ev=PageView&noscript=1"
        /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-53391706-1', 'auto');
        ga('require', 'ec');
        ga('send', 'pageview');

    </script>
    <script type="text/javascript">
        var google_conversion_id = 962769973;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
    </script>

    <!-- Google Tag Manager ID -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-PVVT2N4"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                '//www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PVVT2N4');</script>
    <!-- End Google Tag Manager -->

    <!-- Start of VideoCall -->
    <script type="text/javascript">
        (function (d, src, c) {
            var t = d.scripts[d.scripts.length - 1], s = d.createElement('script');
            s.id = 'la_x2s6df8d';
            s.async = true;
            s.src = src;
            s.onload = s.onreadystatechange = function () {
                var rs = this.readyState;
                if (rs && (rs != 'complete') && (rs != 'loaded')) {
                    return;
                }
                c(this);
            };
            t.parentElement.insertBefore(s, t.nextSibling);
        })(document,
            'https://weshopgl.ladesk.com/scripts/track.js',
            function (e) {
                LiveAgent.createButton('7b252cbe', e);
            });
    </script>
    <!-- End of VideoCall -->

    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <script type="text/javascript">
        (function (d, src, c) {
            var t = d.scripts[d.scripts.length - 1], s = d.createElement('script');
            s.id = 'la_x2s6df8d';
            s.async = true;
            s.src = src;
            s.onload = s.onreadystatechange = function () {
                var rs = this.readyState;
                if (rs && (rs != 'complete') && (rs != 'loaded')) {
                    return;
                }
                c(this);
            };
            t.parentElement.insertBefore(s, t.nextSibling);
        })(document,
            'https://weshopgl.ladesk.com/scripts/track.js',
            function (e) {
                LiveAgent.createButton('04a01e29', e);
            });
    </script>

    <!-- End of LiveChat code -->
    <?php
} elseif ($website->isWSVN()) {
    ?>
    <!-- Start of LiveChat code -->
    <script type="text/javascript">
        (function (d, src, c) {
            var t = d.scripts[d.scripts.length - 1], s = d.createElement('script');
            s.id = 'la_x2s6df8d';
            s.async = true;
            s.src = src;
            s.onload = s.onreadystatechange = function () {
                var rs = this.readyState;
                if (rs && (rs != 'complete') && (rs != 'loaded')) {
                    return;
                }
                c(this);
            };
            t.parentElement.insertBefore(s, t.nextSibling);
        })(document,
            'https://weshopgl.ladesk.com/scripts/track.js',
            function (e) {
                LiveAgent.createButton('4e6235f6', e);
            });
    </script>
    <!-- End of LiveChat code -->
    <?php if ($typeModule == 'amazon-uk') { ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-68960158-5"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-68960158-5');
        </script>
    <?php } elseif ($typeModule == 'amazon-jp') { ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-68960158-6"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-68960158-6');
        </script>

    <?php } ?>

    <!-- Google Tag Manager VN -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                '//www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PF5JM3');</script>

    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-PF5JM3"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager -->


    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-68960158-1', 'auto');
        ga('require', 'ec');
        ga('send', 'pageview');

    </script>
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 945204372;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <?php
} else {
    ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-68960158-1', 'auto');
        ga('send', 'pageview');

    </script>
    <?php

}
?>
