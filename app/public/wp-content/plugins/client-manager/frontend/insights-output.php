<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CMInsightsDashboard {
    private $wpdb;
    private $client_id;
    private $client_data;
    private $welcometext;
    private $client_param;

    private $servicesbutton = true;
    private $onboardingbutton = true;
    private $monthlyactivitybutton = true;
    private $projectsandstatuesbutton = true;
    private $bldsbutton = true;
    private $websiteanaylticsbutton = true;
    private $keywordinformationbutton = true;
    private $uploadportalbutton = true;
    private $billingandadminbutton = true;
    private $supportticketsbutton = true;
    private $socialmediamanagementbutton = true;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->client_id = null;
        $this->client_data = null;
        $this->welcometext = '';
        $this->client_param = isset($_GET['client']) ? sanitize_text_field($_GET['client']) : '';

        $this->process_client();
        $this->determine_needed_dashboard_items();
    }

    private function process_client() {
        if ($this->client_param) {
            // Extract client ID from the parameter
            preg_match('/-(\d+)$/', $this->client_param, $matches);
            if (!empty($matches[1])) {
                $this->client_id = intval($matches[1]);

                // Query the database for client data
                $table_name = $this->wpdb->prefix . 'clients';
                $this->client_data = $this->wpdb->get_row(
                    $this->wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $this->client_id), 
                    ARRAY_A
                );

                if ($this->client_data) {
                    $this->welcometext = "<h2>Welcome, " . esc_html($this->client_data['business_name']) . "</h2>";
                }
            }
        }
    }

    private function determine_needed_dashboard_items() {

        /* Your Services - every service being provided to client
            Website Hosting, maintenance, support (1 hour per month included?)
            Website design/development (notate each project and simple status - complete, in progress, live and hosted...?)
            logo creation (notate each project and simple status - complete, in progress, live and hosted...?)
            Miscellanious project (custom development, tri-fold brochure, etc... not sure where to notate that either)
            monthly marketing services
                Business Listing Distributions
                content/page creation ( not sure where to get this indication...)
                monthly technical seo activities 
                social media management
                paid advertising services
        */

        // 9 database tables - lets first check and see if the client even has an entry in which...
        // Check services_custom_development → $servicesbutton
        $table_name = $this->wpdb->prefix . 'services_custom_development';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $customdevelopment_dbentry = ($this->wpdb->get_var($query) > 0);

        // Check services_website_hosting → $keywordinformationbutton
        $table_name = $this->wpdb->prefix . 'services_website_hosting';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $websitehosting_dbentry = ($this->wpdb->get_var($query) > 0);

        // Check services_logo_design → $onboardingbutton
        $table_name = $this->wpdb->prefix . 'services_logo_design';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $logodesign_dbentry = ($this->wpdb->get_var($query) > 0);
        
        // Check services_misc → $monthlyactivitybutton
        $table_name = $this->wpdb->prefix . 'services_misc';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $servicesmisc_dbentry = ($this->wpdb->get_var($query) > 0);
        
        // Check services_paid_ads_related → $projectsandstatuesbutton
        $table_name = $this->wpdb->prefix . 'services_paid_ads_related';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $paidadservice_dbentry = ($this->wpdb->get_var($query) > 0);

        // Check services_seo_related → $bldsbutton
        $table_name = $this->wpdb->prefix . 'services_seo_related';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $seorelatedservice_dbentry = ($this->wpdb->get_var($query) > 0);
        
        // Check services_social_media_management → $websiteanaylticsbutton
        $table_name = $this->wpdb->prefix . 'services_social_media_management';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $socialmanagement_dbentry = ($this->wpdb->get_var($query) > 0);
        
        // Check services_website_project → $uploadportalbutton
        $table_name = $this->wpdb->prefix . 'services_website_project';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $websiteproject_dbentry = ($this->wpdb->get_var($query) > 0);

        // Check support_tickets → $billingandadminbutton
        $table_name = $this->wpdb->prefix . 'support_tickets';
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE client_id = %d", $this->client_id);
        $supporttickets_dbentry = ($this->wpdb->get_var($query) > 0);
        
        // These all correspond to determining if a button should appear on the dashboard... there will be more of these than the database tables above, as all clients will have billing and admin, for example...
        
        // the default every-clinet-has-this buttons...
        $this->servicesbutton = true;
        $this->billingandadminbutton = true;
        $this->uploadportalbutton = true;
        $this->onboardingbutton = true;
        $this->monthlyactivitybutton = true;

        // simply if there's a single database entry, whether we host the site or not, regardless of other services provided...
        if ( ! $supporttickets_dbentry ) {
            $this->supportticketsbutton = false;
        }

        // If we want the website analytics button... almost in all cases we will. If we built the site, we're connecting to GA4. If we're doing SEO, we're connecting to GA4. Potentially even if we're just hosting and nothing else, we're connecting to GA4...
        if ( ( ! $websiteproject_dbentry ) && ( ! $websitehosting_dbentry  ) && ( ! $seorelatedservice_dbentry  )  ) {
            $this->websiteanaylticsbutton = false;
        }

        // keyword data... we'll have this if we're providing SEO services and if it's website-only and we just want that data there for potential upsell...
        if ( ( ! $websiteproject_dbentry ) && ( ! $websitehosting_dbentry  ) && ( ! $seorelatedservice_dbentry  )  ) {
            $this->keywordinformationbutton = false;
        }

        // if we're providing SEO services, we're doing blds...
        if ( ! $seorelatedservice_dbentry ) {
            $this->bldsbutton = false;
        }

        // If we're doing any kind of project for the client... probably almost every client, but...
        if ( ( ! $websiteproject_dbentry ) || ( ! $customdevelopment_dbentry  ) || ( ! $logodesign_dbentry  )  ) {
            $this->projectsandstatuesbutton = false;
        }

        // If we're doing any kind of project for the client... probably almost every client, but...
        if ( ! $socialmanagement_dbentry  ) {
            $this->socialmediamanagementbutton = false;
        }
    }

    private function process_client_services() {
        
    }

    private function render_opening_html() {
        return '<div class="cm-insights-top-main-holder">';
    }

    private function render_left_menu_open_html() {
        return '
        <div class="cm-insights-top-left-menu-holder">
            <div class="cm-insights-top-left-menu-buttons-holder">';
    }

    private function render_left_menu_button1_html() {
        if ($this->servicesbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-services-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Your<br/>Services
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button2_html() {
        if ($this->onboardingbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-onboarding-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Onboarding<br/>Status
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button3_html() {
        if ($this->monthlyactivitybutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-monthlyactivity-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Monthly<br/>Activity
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button4_html() {
        if ($this->projectsandstatuesbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-projectsandstatues-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Projects<br/>& Status
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button5_html() {
        if ($this->bldsbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-blds-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Business<br/>Listings
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button6_html() {
        if ($this->websiteanaylticsbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-websiteanalytics-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Website<br/>Analytics
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button7_html() {
        if ($this->keywordinformationbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-keywordinformation-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Keyword<br/>Information
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button8_html() {
        if ($this->uploadportalbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-uploadportal-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Upload<br/>Portal
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button9_html() {
        if ($this->billingandadminbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-billingandadmin-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Billing<br/>& Admin
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button10_html() {
        if ($this->supportticketsbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-billingandadmin-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Support<br/>Tickets
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_button11_html() {
        if ($this->socialmediamanagementbutton) {
            return '
            <button class="lur-services-website-design-button cm-dash-billingandadmin-button">
                <div class="lur-services-website-design-finger-holder">
                    <img decoding="async" class="lur-services-website-design-finger-white" 
                        src="/wp-content/themes/total-child-theme/images/tap-white.webp" 
                        alt="a symbol to encourage a click or tap">
                </div>Social Media<br/>Management
            </button>';
        } else {
            return '';
        }
    }

    private function render_left_menu_close_html() {
        return '
            </div>
        </div>';
    }

    private function render_right_info_open_html() {
        return '
        <div class="cm-insights-right-info-menu-holder">';
    }

    private function render_right_info_intro_html() {
        return '
            <div class="cm-indiv-left-button-indiv-top cm-dash-intro-container">
                ' . $this->welcometext;
    }

    // Updated render_right_info_button1_html function with a loop through services tables
    private function render_right_info_button1_html() {
        if ($this->servicesbutton) {
            $current_services_html = '';

            // Define an array of services with table names, human-readable names,
            // and the check type and column to use for determining service status.
            $services = array(
                array(
                    'table'       => $this->wpdb->prefix . 'services_website_project',
                    'service_name'=> 'Website Project',
                    'check_type'  => 'project_status'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_website_hosting',
                    'service_name'=> 'Website Hosting',
                    'check_type'  => 'date',
                    'column'      => 'hosting_end_date'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_website_maintenance',
                    'service_name'=> 'Website Maintenance',
                    'check_type'  => 'date',
                    'column'      => 'support_end_date'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_logo_design',
                    'service_name'=> 'Logo Design',
                    'check_type'  => 'non_empty',
                    'column'      => 'zipdownloadurl'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_social_media_management',
                    'service_name'=> 'Social Media Management',
                    'check_type'  => 'non_empty',
                    'column'      => 'project_completion_date'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_seo_related',
                    'service_name'=> 'SEO Related',
                    'check_type'  => 'date',
                    'column'      => 'enddate'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_paid_ads_related',
                    'service_name'=> 'Paid Ads Related',
                    'check_type'  => 'non_empty',
                    'column'      => 'project_completion_date'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_custom_development',
                    'service_name'=> 'Custom Development',
                    'check_type'  => 'non_empty',
                    'column'      => 'project_completion_date'
                ),
                array(
                    'table'       => $this->wpdb->prefix . 'services_misc',
                    'service_name'=> 'Miscellaneous',
                    'check_type'  => 'non_empty',
                    'column'      => 'project_completion_date'
                )
            );

            // Loop through each service table and build its corresponding HTML.
            foreach ($services as $service) {
                $table = $service['table'];
                $service_name = $service['service_name'];
                $check_type = $service['check_type'];
                $column = isset($service['column']) ? $service['column'] : '';
                $query = $this->wpdb->prepare("SELECT * FROM $table WHERE client_id = %d LIMIT 1", $this->client_id);
                $row = $this->wpdb->get_row($query);
                $variant_html = '';

                if (!$row) {
                    // No row found → Variant 1: Never contracted (gray icon)
                    $variant_html = '<div class="cm-all-services-individual-holder">
                        <div class="cm-all-services-individual-holder-img-and-tooltip">
                            <img class="services-indicator services-indicator-never-contracted" src="' . CM_PLUGIN_URL . '/assets/img/Service-Gray.svg" />
                            <img class="cm-dashboard-general-tooltip-icon" src="' . CM_PLUGIN_URL . '/assets/img/General-Tooltip.svg" data-tooltiptext="This is tooltip text that will be used sometime in the future. Placeholder text for now." />
                        </div>
                        <div class="cm-all-services-individual-holder-service-title">
                            <p>' . $service_name . '</p>
                        </div>
                    </div>';
                } else {
                    // Row exists. Determine if the service is complete / not active (variant 2) or active (variant 3).
                    $use_variant2 = false;
                    if ($check_type == 'project_status') {
                        $status = strtolower(trim($row->project_status));
                        if ($status === 'complete' || $status === 'paused') {
                            $use_variant2 = true;
                        }
                    } elseif ($check_type == 'date') {
                        $date_value = $row->{$column};
                        if (!empty($date_value) && strtotime($date_value) < time()) {
                            $use_variant2 = true;
                        }
                    } elseif ($check_type == 'non_empty') {
                        $value = $row->{$column};
                        if (!empty($value)) {
                            $use_variant2 = true;
                        }
                    }

                    if ($use_variant2) {
                        // Variant 2: Service is complete/not currently provided (yellow icon)
                        $variant_html = '<div class="cm-all-services-individual-holder">
                            <div class="cm-all-services-individual-holder-img-and-tooltip">
                                <img class="services-indicator services-indicator-notcurrently-contracted" src="' . CM_PLUGIN_URL . '/assets/img/Service-Yellow.svg" />
                                <img class="cm-dashboard-general-tooltip-icon" src="' . CM_PLUGIN_URL . '/assets/img/General-Tooltip.svg" data-tooltiptext="This is tooltip text that will be used sometime in the future. Placeholder text for now." />
                            </div>
                            <div class="cm-all-services-individual-holder-service-title">
                                <p>' . $service_name . '</p>
                            </div>
                        </div>';
                    } else {
                        // Variant 3: Service is currently provided (green icon)
                        $variant_html = '<div class="cm-all-services-individual-holder">
                            <div class="cm-all-services-individual-holder-img-and-tooltip">
                                <img class="services-indicator services-indicator-notcurrently-contracted" src="' . CM_PLUGIN_URL . '/assets/img/Service-Green.svg" />
                                <img class="cm-dashboard-general-tooltip-icon" src="' . CM_PLUGIN_URL . '/assets/img/General-Tooltip.svg" data-tooltiptext="This is tooltip text that will be used sometime in the future. Placeholder text for now." />
                            </div>
                            <div class="cm-all-services-individual-holder-service-title">
                                <p>' . $service_name . '</p>
                            </div>
                        </div>';
                    }
                }
                $current_services_html .= $variant_html;
            }

            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-services-button"> 
                <p>This is corresponding button 1 content (Your Services)</p>
                <div class="cm-all-services-holder">
                    <div class="cm-all-services-inner-holder">
                        ' . $current_services_html . '
                    </div>
                </div>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button2_html() {
        if ($this->onboardingbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-onboarding-button"> 
                <p>This is corresponding button 2 content (Onboarding Status)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button3_html() {
        if ($this->monthlyactivitybutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-monthlyactivity-button"> 
                <p>This is corresponding button 3 content (Monthly Activity)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button4_html() {
        if ($this->projectsandstatuesbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-projectsandstatues-button"> 
                <p>This is corresponding button 4 content (Projects & Status)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button5_html() {
        if ($this->bldsbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-blds-button"> 
                <p>This is corresponding button 5 content (Business Listings)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button6_html() {
        if ($this->websiteanaylticsbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-websiteanalytics-button"> 
                <p>This is corresponding button 6 content (Website Analytics)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button7_html() {
        if ($this->keywordinformationbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-keywordinformation-button"> 
                <p>This is corresponding button 7 content (Keyword Information)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button8_html() {
        if ($this->uploadportalbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-uploadportal-button"> 
                <p>This is corresponding button 8 content (Upload Portal)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button9_html() {
        if ($this->billingandadminbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-billingandadmin-button"> 
                <p>This is corresponding button 9 content (Billing & Admin)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button10_html() {
        if ($this->supportticketsbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-supporttickets-button"> 
                <p>This is corresponding button 10 content (Support Tickets)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_button11_html() {
        if ($this->supportticketsbutton) {
            return '
            <div class="cm-right-info-inner-content-type-holder cm-right-info-inner-content-type-holder-socialmediamanagement-button"> 
                <p>This is corresponding button 11 content (Social Media Management)</p>
            </div>';
        } else {
            return '';
        }
    }

    private function render_right_info_close_html() {
        return '
            </div>
        </div>';
    }

    private function render_closing_html() {
        return '</div>';
    }

    public function display_dashboard() {
        echo $this->render_opening_html();
        echo $this->render_left_menu_open_html();

        echo $this->render_left_menu_button1_html();
        echo $this->render_left_menu_button2_html();
        echo $this->render_left_menu_button3_html();
        echo $this->render_left_menu_button4_html();
        echo $this->render_left_menu_button5_html();
        echo $this->render_left_menu_button6_html();
        echo $this->render_left_menu_button7_html();
        echo $this->render_left_menu_button8_html();
        echo $this->render_left_menu_button11_html();
        echo $this->render_left_menu_button10_html();
        echo $this->render_left_menu_button9_html();

        echo $this->render_left_menu_close_html();
        echo $this->render_right_info_open_html();
        echo $this->render_right_info_intro_html();

        echo $this->render_right_info_button1_html();
        echo $this->render_right_info_button2_html();
        echo $this->render_right_info_button3_html();
        echo $this->render_right_info_button4_html();
        echo $this->render_right_info_button5_html();
        echo $this->render_right_info_button6_html();
        echo $this->render_right_info_button7_html();
        echo $this->render_right_info_button8_html();
        echo $this->render_right_info_button11_html();
        echo $this->render_right_info_button10_html();
        echo $this->render_right_info_button9_html();

        echo $this->render_right_info_close_html();
        echo $this->render_closing_html();
    }
}

// Initialize and render the dashboard
$dashboard = new CMInsightsDashboard();
$dashboard->display_dashboard();
