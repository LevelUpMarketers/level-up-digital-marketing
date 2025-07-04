<?php
/**
 * Total Child theme functions.
 */

function add_analytics_js_to_head() {
    ?>
   <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HGEZCCCQGG"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-HGEZCCCQGG');
    </script>
    <?php
}
add_action('wp_head', 'add_analytics_js_to_head');

function remove_wpautop_filter() {
    remove_filter( 'the_content', 'wpautop' );
    remove_filter( 'the_excerpt', 'wpautop' );
}

add_action( 'init', 'remove_wpautop_filter' );


function enqueue_homepage_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'homepage-style', get_stylesheet_directory_uri() . '/styles/homepage.css', array(), '1.0', 'all' );
}

function enqueue_websitedesign_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'websitedesign-style', get_stylesheet_directory_uri() . '/styles/websitedesign.css', array(), '4.0', 'all' );
}

function enqueue_seo_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'seo-style', get_stylesheet_directory_uri() . '/styles/seo.css', array(), '4.0', 'all' );
}

function enqueue_contact_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'contact-style', get_stylesheet_directory_uri() . '/styles/contact.css', array(), '1.0', 'all' );
}

function enqueue_whowehelp_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'whowehelp-style', get_stylesheet_directory_uri() . '/styles/whowehelp.css', array(), '1.0', 'all' );
}

function enqueue_locations_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'locations-style', get_stylesheet_directory_uri() . '/styles/locations.css', array(), '1.0', 'all' );
}

function enqueue_services_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'services-style', get_stylesheet_directory_uri() . '/styles/services.css', array(), '1.0', 'all' );
}

function enqueue_learn_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'learn-style', get_stylesheet_directory_uri() . '/styles/learn.css', array(), '1.0', 'all' );
}

function enqueue_insights_dashboard_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'learn-style', get_stylesheet_directory_uri() . '/styles/insightsdashboard.css', array(), '1.0', 'all' );
}

function enqueue_indiv_blog_stylesheet() {
    // Enqueue your custom stylesheet
    wp_enqueue_style( 'indiv-blog-style', get_stylesheet_directory_uri() . '/styles/indiv-blog.css', array(), '1.0', 'all' );
}

function enqueue_theme_styles_conditionally() {
    if ( is_front_page() ) {
        enqueue_homepage_stylesheet();
    }

    if ( false !== stripos( $_SERVER['REQUEST_URI'], 'services/website-design' ) ) {
        enqueue_websitedesign_stylesheet();
    }

    if ( false !== stripos( $_SERVER['REQUEST_URI'], 'services/search-engine-optimization' ) ) {
        enqueue_seo_stylesheet();
    }

    if ( is_page( 'contact' ) ) {
        enqueue_contact_stylesheet();
    }

    if ( is_page( 'who-we-help' ) ) {
        enqueue_whowehelp_stylesheet();
    }

    if ( is_page( 'locations' ) ) {
        enqueue_locations_stylesheet();
    }

    if ( is_page( 'services' ) ) {
        enqueue_services_stylesheet();
    }

    if ( is_page( 'learn' ) || is_search() || false !== stripos( $_SERVER['REQUEST_URI'], 'learn/category/' ) || false !== stripos( $_SERVER['REQUEST_URI'], 'learn/author/' ) ) {
        enqueue_learn_stylesheet();
    }

    if ( false !== stripos( $_SERVER['REQUEST_URI'], 'insights-dashboard' ) || is_search() ) {
        enqueue_insights_dashboard_stylesheet();
    }

    if ( false === stripos( $_SERVER['REQUEST_URI'], 'category' ) && false !== stripos( $_SERVER['REQUEST_URI'], 'learn' ) && 'learn/' !== substr( $_SERVER['REQUEST_URI'], -6 ) && false === stripos( $_SERVER['REQUEST_URI'], '/author/' ) ) {
        enqueue_indiv_blog_stylesheet();
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_styles_conditionally' );


// Printing Page Template
if( current_user_can( 'manage_options' ) ) {
    // Print the saved global 
    //printf( '<div><strong>Current template:</strong> %s</div>', get_page_template() ); 

}


function sitewide_add_structure_to_head() {
    ?>
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "LocalBusiness",
          "@id": "https://levelupmarketers.com/#mymarketingbusiness",
          "name": "Level Up Digital Marketing",
          "url": "https://levelupmarketers.com/",
          "logo": "https://levelupmarketers.com/wp-content/uploads/2023/07/Level-Up-Digital-Marketing-01.svg",
          "image": "https://levelupmarketers.com/wp-content/uploads/2023/07/Level-Up-Digital-Marketing-01.svg",
          "telephone": "(804) 489-8188",
          "email": "contact@levelupmarketers.com",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "1011 E Main St Suite 222",
            "addressLocality": "Richmond",
            "addressRegion": "VA",
            "postalCode": "23219",
            "addressCountry": "US"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": 37.5374024,
            "longitude": -77.43515
          },
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
              "opens": "08:00",
              "closes": "18:00"
            }
          ],
          "sameAs": [
            "https://www.facebook.com/LevelUpDigitalMarketing",
            "https://www.linkedin.com/company/levelup-digital-marketing"
          ],
          "priceRange": "$$"
        }
    </script>

    <?php
}

function webdesign_add_structure_to_head() {
    ?>
        <!-- JSON-LD markup generated by Google Structured Data Markup Helper. -->
        <script type="application/ld+json">
        [
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "How can a website bring me leads?",
              "text": "How can a website bring me leads?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "People in your local area are searching online every day for the services you provide. The best way for your business to appear in front of those potential customers is by having a fully-optimized website. 10 years ago, it was enough to just have a website - today the online landscape is much more complex and competitive. You not only need a website, but you need one done right, built with attention-to-detail and supplemented by ongoing Digital Marketing services. If your goal is to grow your business, then prioritizing an investment in your online presence is a wise move.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "Why is website performance important?",
              "text": "Why is website performance important?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "An important factor that determines if your website appears on Google above competitors is how quickly your website loads. Dozens of items impact website performance, including optimized images, reducing the amount of required code, being hosted on an optimized server, etc. Google also prioritizes how a website performs on mobile devices first, and it considers code still being loaded behind-the-scenes that you don't see. Make sure you're working with someone that has the technical knowledge required to fine-tune the performance of your website.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "Why should my website be mobile-friendly?",
              "text": "Why should my website be mobile-friendly?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "In 2017, the majority of internet traffic came from mobile devices, and this trend continues today. Building a website to work on all devices means taking into account slower internet speeds that mobile devices sometimes operate on, and also ensuring the design of your website translates well to a taller, narrower device such as a smartphone. Google also prioritizes the experience your website visitors receive on a mobile device over a desktop or laptop - so being optimized for mobile devices becomes an important business consideration.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "Why rebuild a website from scratch?",
              "text": "Why rebuild a website from scratch?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "If your goal is to have the best chance possible to be found online by potential customers, clients, and patients, then half-measures such as a /'website refresh/' or a /'new coat of paint/' just won't work. You need the fundamentals of your website to be built with Digital Marketing in mind from the ground up. Too often companies choose a cheaper option, which only results in delaying the need for a full rebuild, after which more money has been spent in total than if it had been done right the first time. While every website should be assessed to determine specific needs, chances are doing it right means investing in a full website rebuild.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "Why is a conversion-focused design important?",
              "text": "Why is a conversion-focused design important?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "A conversion-focused design means making it as easy as possible for your website visitors to find your phone number, email address, physical address, a contact form, or vital info that helps them with their needs. A conversion-focused design allows for these elements to always be within easy reach, regardless of what device your website visitor is using, or where within your website they're at. The moment your visitors feel they have to work to find this information is the moment they return to Google and investigate your competitors.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "How important are the words on my website?",
              "text": "How important are the words on my website?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Quality written content answers the questions your website visitors have. Quality content also communicates your expertise in your industry. Most importantly though, the more quality content you have, the more chances you have to show up in search results. Think of the written content on your website as high-quality /'virtual real estate/' that Google provides to people searching for services you offer, and to those seeking answers to specific questions. Your website visitors probably won't read all of your content, but Google will. Give yourself the best chance to capture all types of prospects and leads with healthy amounts of high-quality written content.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          },
          {
            "@context": "http://schema.org",
            "@type": "QAPage",
            "mainEntity": {
              "@type": "Question",
              "name": "How important are the words on my website?",
              "text": "How important are the words on my website?",
              "answerCount": "1",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Quality written content answers the questions your website visitors have. Quality content also communicates your expertise in your industry. Most importantly though, the more quality content you have, the more chances you have to show up in search results. Think of the written content on your website as high-quality /'virtual real estate/' that Google provides to people searching for services you offer, and to those seeking answers to specific questions. Your website visitors probably won't read all of your content, but Google will. Give yourself the best chance to capture all types of prospects and leads with healthy amounts of high-quality written content.",
                "url": "https://levelupmarketers.com/services/website-design/#websitedoneright"
              }
            }
          }

        ]
        </script>
    <?php
}

add_action('wp_head', 'sitewide_add_structure_to_head');

function maybe_add_webdesign_structure() {
    if ( false !== stripos( $_SERVER['REQUEST_URI'], 'services/website-design' ) ) {
        webdesign_add_structure_to_head();
    }
}
add_action( 'wp_head', 'maybe_add_webdesign_structure' );










add_action( 'wp_print_styles',     'my_deregister_styles', 100 );
function my_deregister_styles()    { 
   wp_deregister_style( 'dashicons' );
   wp_deregister_style( 'ticons' );
   wp_deregister_style( 'vcex-shortcodes' );
   wp_deregister_style( 'fontawesome' );
   wp_deregister_style('dashicons');
}

function remove_scripts() {
    wp_dequeue_script('hoverIntent');
    wp_deregister_script('hoverIntent');
    wp_dequeue_script('total');
    wp_deregister_script('total');
}
add_action('wp_enqueue_scripts', 'remove_scripts', 100);

// Load the parent style.css file.
function total_child_enqueue_parent_theme_style() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme( 'Total' )->get( 'Version' )
    );
}
//add_action( 'wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style' );

function enqueue_custom_javascript() {

    if ( is_front_page() ) {
        wp_enqueue_script(
            'custom-script', // Handle for the script (can be any unique name)
            '/wp-content/themes/total-child-theme/javascript/homepage.js', // Path to your JavaScript file
            array('jquery'), // Dependencies (if any)
            '1.0', // Script version (optional)
            true // Whether to load the script in the footer (true) or the header (false)
        );
    }

    if (  false !== stripos( "$_SERVER[REQUEST_URI]", 'services/website-design')  ) {
        wp_enqueue_script(
            'custom-script', // Handle for the script (can be any unique name)
            '/wp-content/themes/total-child-theme/javascript/services-website.js', // Path to your JavaScript file
            array('jquery'), // Dependencies (if any)
            '1.0', // Script version (optional)
            true // Whether to load the script in the footer (true) or the header (false)
        );
    }

    if (  false !== stripos( "$_SERVER[REQUEST_URI]", 'insights-dashboard')  ) {
        wp_enqueue_script(
            'custom-script', // Handle for the script (can be any unique name)
            '/wp-content/themes/total-child-theme/javascript/insights-dashboard.js', // Path to your JavaScript file
            array('jquery'), // Dependencies (if any)
            '1.0', // Script version (optional)
            true // Whether to load the script in the footer (true) or the header (false)
        );
    }

    if (  false !== stripos( "$_SERVER[REQUEST_URI]", 'services/search-engine-optimization')  ) {
        wp_enqueue_script(
            'custom-script', // Handle for the script (can be any unique name)
            '/wp-content/themes/total-child-theme/javascript/services-seo.js', // Path to your JavaScript file
            array('jquery'), // Dependencies (if any)
            '1.0', // Script version (optional)
            true // Whether to load the script in the footer (true) or the header (false)
        );
    }

    if (  false !== stripos( "$_SERVER[REQUEST_URI]", 'services/')  ) {
        wp_enqueue_script(
            'custom-script', // Handle for the script (can be any unique name)
            '/wp-content/themes/total-child-theme/javascript/services.js', // Path to your JavaScript file
            array('jquery'), // Dependencies (if any)
            '1.0', // Script version (optional)
            true // Whether to load the script in the footer (true) or the header (false)
        );
    }
    

}
add_action('wp_enqueue_scripts', 'enqueue_custom_javascript');

/*
 * White list functions for use in Total Theme Core shortcodes.
 */
define( 'VCEX_CALLBACK_FUNCTION_WHITELIST', [] );

function remove_default_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false);
    }
}
//add_action('wp_enqueue_scripts', 'remove_default_jquery');

function allow_svg_upload($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');


function homepage_custom_title_tag( $title )
{
    if ( is_front_page() ) {
        return 'Level Up Digital Marketing | Expert Website Design, SEO, Paid Search & Content Marketing Services';
    } elseif (  false !== stripos( "$_SERVER[REQUEST_URI]", 'services/search-engine-optimization')  ) {
        return "SEO Services by Level Up Digital Marketing | Enhance Your Rankings & Visibility";
    } elseif (  false !== stripos( "$_SERVER[REQUEST_URI]", 'services/website-design')  ) {
        return "Professional Website Design Services by Level Up Digital Marketing | Elevate Your Online Presence";
    } elseif (  false !== stripos( "$_SERVER[REQUEST_URI]", 'contact')  ) {
        return "Get in Touch with Level Up Digital Marketing | Reach Out for Expert Digital Marketing Solutions";
    } else {
        return $title;
    }
}

add_filter( 'pre_get_document_title', 'homepage_custom_title_tag' );

add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_tax()) { //for custom post types
        $title = sprintf(__('%1$s'), single_term_title('', false));
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});