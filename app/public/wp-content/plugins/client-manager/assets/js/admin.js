// AJAX/Database Functions
jQuery(document).ready(function($) {

   function ajaxRequest(action, data, successCallback, errorCallback, isFormData = false) {
        if (isFormData) {
            data.append('nonce', customAjax.nonce); // Append nonce separately for FormData
            data.append('action', action); // Append action separately for FormData
        } else {
            data.nonce = customAjax.nonce; // Regular object
            data.action = action; // Regular object
        }

        console.log("AJAX Request - Action:", action);
        console.log("AJAX Request - Data:", data);

        $.ajax({
            url: customAjax.ajax_url,
            type: 'POST',
            data: data,
            processData: !isFormData, // Don't process FormData (files)
            contentType: isFormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (response) {
                console.log("AJAX Success Response:", response);
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.error("JSON Parse Error:", response);
                        alert("Invalid server response.");
                        return;
                    }
                }
                successCallback(response);
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", xhr.status, xhr.statusText, xhr.responseText);
                if (errorCallback) errorCallback(xhr.responseText || error);
            }
        });
    }




    // For validating text innpuut URL fields
    var knownTLDs = [
        "com", "org", "net", "int", "edu", "gov", "mil", "arpa",
        "aero", "biz", "coop", "info", "museum", "name", "pro",
        "asia", "cat", "jobs", "mobi", "tel", "travel", "xxx",
        "xyz", "top", "win", "club", "online", "site", "shop",
        "tech", "store", "blog", "io", "me", "us", "uk", "ru",
        "de", "jp", "fr", "au", "in", "cn", "nl", "br", "za",
        "pl", "gr", "kr", "se", "no", "fi", "dk", "ch", "it",
        "es", "pt", "mx", "tr", "ar", "sg", "my", "th", "hk",
        "tw", "vn", "co", "za", "ae", "sa", "nz", "ke", "ng",
        "ug", "tz", "zw", "il", "qa", "lb", "pk", "bd", "lk"
    ];

    // These are the classes that are on each text input that I want to validate a correct URL for
    var urlTargetClasses = ['jre-validateurl','insert-other-classname-here'];

    // Function to determine is a text input is a valid url
    function isValidURL(value) {
        var parts = value.split('.');
        if (parts.length < 2) {
            return false; // A valid URL must have at least one dot
        }
        var tld = parts[parts.length - 1];
        return knownTLDs.includes(tld.toLowerCase());
    }

    // The usage determining is a text inpuut is a valid URL
    urlTargetClasses.forEach(function(className) {
        $(document).on('blur', 'input[type="text"].' + className, function() {
            var value = $(this).val();
            if (!isValidURL(value)) {
                $(this).css('border', '2px solid red');
                $(this).addClass('notvalid');
            } else {
                $(this).css('border', ''); // Remove the red border if valid
                $(this).removeClass('notvalid');
            }
        });
    });




    // Adding a new Client
    $('#cm_add_client_button').on('click', function() {
        var data = {
            action: 'cm_add_client',
            nonce: customAjax.nonce,
            business_name: $('#cm_business_name').val(),
            client_since_date: $('#cm_client_since_date').val(),
            logo: $('#cm_logo').val(),
            main_location_address_2: $('#cm_main_location_address_2').val(),
            main_location_name: $('#cm_main_location_name').val(),
            main_location_state: $('#cm_main_location_state').val(),
            main_location_city: $('#cm_main_location_city').val(),
            main_location_street_address: $('#cm_main_location_street_address').val(),
            main_location_zip: $('#cm_main_location_zip').val(),
            main_poc_email: $('#cm_main_poc_email').val(),
            main_poc_first_name: $('#cm_main_poc_first_name').val(),
            main_poc_headshot: $('#cm_main_poc_headshot').val(),
            main_poc_last_name: $('#cm_main_poc_last_name').val(),
            main_poc_phone: $('#cm_main_poc_phone').val(),
            main_poc_title: $('#cm_main_poc_title').val(),
            poc_2_email: $('#cm_poc_2_email').val(),
            poc_2_first_name: $('#cm_poc_2_first_name').val(),
            poc_2_headshot: $('#cm_poc_2_headshot').val(),
            poc_2_last_name: $('#cm_poc_2_last_name').val(),
            poc_2_phone: $('#cm_poc_2_phone').val(),
            poc_2_title: $('#cm_poc_2_title').val(),
            poc_3_email: $('#cm_poc_3_email').val(),
            poc_3_first_name: $('#cm_poc_3_first_name').val(),
            poc_3_headshot: $('#cm_poc_3_headshot').val(),
            poc_3_last_name: $('#cm_poc_3_last_name').val(),
            poc_3_phone: $('#cm_poc_3_phone').val(),
            poc_3_title: $('#cm_poc_3_title').val(),
            cm_main_analytics_prop_id: $('#cm_main_analytics_prop_id').val(),
            second_location_address_2: $('#cm_second_location_address_2').val(),
            second_location_name: $('#cm_second_location_name').val(),
            second_location_state: $('#cm_second_location_state').val(),
            second_location_city: $('#cm_second_location_city').val(),
            second_location_street_address: $('#cm_second_location_street_address').val(),
            second_location_zip: $('#cm_second_location_zip').val()
        };

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Editing an existing Client
    $('.cm_edit_client_button').on('click', function() {

        var correctform = $(this).parent().prev();
        var clientid = $(this).attr('data-clientid');

        var data = {
            action: 'cm_edit_client',
            nonce: customAjax.nonce,
            client_id: clientid,
            business_name: correctform.find('#cm_business_name').val(),
            client_since_date: correctform.find('#cm_client_since_date').val(),
            date_of_onboarding: correctform.find('#cm_date_of_onboarding').val(),
            logo: correctform.find('#cm_logo-' + clientid).val(),
            main_location_address_2: correctform.find('#cm_main_location_address_2').val(),
            main_location_name: correctform.find('#cm_main_location_name').val(),
            main_location_state: correctform.find('#cm_main_location_state').val(),
            main_location_city: correctform.find('#cm_main_location_city').val(),
            main_location_street_address: correctform.find('#cm_main_location_street_address').val(),
            main_location_zip: correctform.find('#cm_main_location_zip').val(),
            main_poc_email: correctform.find('#cm_main_poc_email').val(),
            main_poc_first_name: correctform.find('#cm_main_poc_first_name').val(),
            main_poc_headshot: correctform.find('#cm_main_poc_headshot-' + clientid).val(),
            main_poc_last_name: correctform.find('#cm_main_poc_last_name').val(),
            main_poc_phone: correctform.find('#cm_main_poc_phone').val(),
            main_poc_title: correctform.find('#cm_main_poc_title').val(),
            poc_2_email: correctform.find('#cm_poc_2_email').val(),
            poc_2_first_name: correctform.find('#cm_poc_2_first_name').val(),
            poc_2_headshot: correctform.find('#cm_poc_2_headshot-' + clientid).val(),
            poc_2_last_name: correctform.find('#cm_poc_2_last_name').val(),
            poc_2_phone: correctform.find('#cm_poc_2_phone').val(),
            poc_2_title: correctform.find('#cm_poc_2_title').val(),
            poc_3_email: correctform.find('#cm_poc_3_email').val(),
            poc_3_first_name: correctform.find('#cm_poc_3_first_name').val(),
            poc_3_headshot: correctform.find('#cm_poc_3_headshot-' + clientid).val(),
            poc_3_last_name: correctform.find('#cm_poc_3_last_name').val(),
            poc_3_phone: correctform.find('#cm_poc_3_phone').val(),
            poc_3_title: correctform.find('#cm_poc_3_title').val(),
            cm_main_analytics_prop_id: correctform.find('#cm_main_analytics_prop_id').val(),
            second_location_address_2: correctform.find('#cm_second_location_address_2').val(),
            second_location_name: correctform.find('#cm_second_location_name').val(),
            second_location_state: correctform.find('#cm_second_location_state').val(),
            second_location_city: correctform.find('#cm_second_location_city').val(),
            second_location_street_address: correctform.find('#cm_second_location_street_address').val(),
            second_location_zip: correctform.find('#cm_second_location_zip').val()
        };

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while editing the client.');
            }
        });
    });

    // Editing an existing Website Project, to include its Creative Brief and Launch Checklist.
    $(document).on('click', '.cm-edit-service-website-project', function() {

        var websiteid = $(this).attr('data-websiteid');
        var cbiid = $(this).attr('data-cbiid');
        var lcid = $(this).attr('data-lcid');
        var data = {};
        var creativeBriefData = {};
        var launchChecklistData = {};

        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

        data['action'] = 'cm_service_website_edit';
        data['nonce'] = customAjax.nonce;
        data['websiteid'] = websiteid;
        data['cbiid'] = cbiid;
        data['lcid'] = lcid;

        $.post(customAjax.ajax_url, data, function(response) {
            if ( 'success' === response ) {
                console.log( 'website project updated' );

                // After updating the actual websiite project - let's update the Creative Brief
                // Find all input and textarea elements within elements that have the class name '.cm-indiv-subtitle-inner-form'
                var creativebriefElements = parent.find('.cm-indiv-subtitle-inner-form-creative-brief input, .cm-indiv-subtitle-inner-form-creative-brief select, .cm-indiv-subtitle-inner-form-creative-brief textarea');


                // Iterate over each subtitle element and add to the creativeBriefData object
                creativebriefElements.each(function() {
                    var key = $(this).attr('id');
                    var value = $(this).val();

                    if (key) {
                        creativeBriefData[key] = value;
                    }
                });

                creativeBriefData['action'] = 'cm_service_website_creative_brief_edit';
                creativeBriefData['nonce'] = customAjax.nonce;
                creativeBriefData['websiteid'] = websiteid;
                creativeBriefData['cbiid'] = cbiid;
                creativeBriefData['lcid'] = lcid;

                $.post(customAjax.ajax_url, creativeBriefData, function(response) {

                    console.log('in the ajax for launch checklist');

                    if ( 'success' === response ) {
                        console.log( 'creative brief updated' );
                        // Now update the Launch Checklist!
                        // Find all input and textarea elements within elements that have the class name '.cm-indiv-subtitle-inner-form'
                        var launchChecklistElements = parent.find('.cm-indiv-subtitle-inner-form-launch-checklist input, .cm-indiv-subtitle-inner-form-launch-checklist select');

                        // Iterate over each subtitle element and add to the creativeBriefData object
                        launchChecklistElements.each(function() {
                            var key = $(this).attr('id');
                            var value = $(this).val();

                            if (key) {
                                launchChecklistData[key] = value;
                            }
                        });

                        launchChecklistData['action'] = 'cm_service_website_launch_checklist_edit';
                        launchChecklistData['nonce'] = customAjax.nonce;
                        launchChecklistData['websiteid'] = websiteid;
                        launchChecklistData['cbiid'] = cbiid;
                        launchChecklistData['lcid'] = lcid;

                        $.post(customAjax.ajax_url, launchChecklistData, function(response) {

                            if ( 'success' === response ) {
                                console.log( 'launch checklist updated' );
                            } else {

                            }


                        });
                    } else {
                        console.log('something failed');
                    }


                });
            } else {
                alert('An error occurred while editing the website project.');
            }
        });



        console.log(data);
        console.log(creativeBriefData);
        console.log(launchChecklistData); 
    });


    // Editing an existing Website Project, to include its Creative Brief and Launch Checklist.
    $(document).on('click', '.cm-edit-service-website-hosting-service', function() {

        var hostingid = $(this).attr('data-hostingid');
        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        var data = {};

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

        data['action'] = 'cm_service_hosting_edit';
        data['nonce'] = customAjax.nonce;
        data['hostingid'] = hostingid;

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {

            if ( 'success' === response ) {
                alert('Successfully edited a hosting service!');
            } else {
                console.log(response);
            }

        });
    });

    // Editing an existing SEO service.
    $(document).on('click', '.cm-edit-service-seo-service', function() {


/*



        var hostingid = $(this).attr('data-hostingid');
        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        var data = {};

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

*/

        var gbplink1 = $(this).parent().find('#gbp1link').val();
        var gbpaccess1 = $(this).parent().find('#gbp1access').val();
        var finalgbp1 = gbplink1 + ',' + gbpaccess1;

        var gbplink2 = $(this).parent().find('#gbp2link').val()
        var gbpaccess2 = $(this).parent().find('#gbp2access').val();
        var finalgbp2 = gbplink2 + ',' + gbpaccess2;

        var gbplink3 = $(this).parent().find('#gbp3link').val()
        var gbpaccess3 = $(this).parent().find('#gbp3access').val();
        var finalgbp3 = gbplink3 + ',' + gbpaccess3;

        var gbplink4 = $(this).parent().find('#gbp4link').val()
        var gbpaccess4 = $(this).parent().find('#gbp4access').val();
        var finalgbp4 = gbplink4 + ',' + gbpaccess4;

        var gbplink5 = $(this).parent().find('#gbp5link').val()
        var gbpaccess5 = $(this).parent().find('#gbp5access').val();
        var finalgbp5 = gbplink5 + ',' + gbpaccess5;


        var clientid = $(this).attr('data-clientid');
        var seoentryid = $(this).attr('data-seoentryid');

        var data = {
            action: 'cm_service_seo_edit',
            nonce: customAjax.nonce,
            clientid: clientid,
            seoentryid: seoentryid,
            service_unique_name: $(this).parent().find('#service_unique_name').val(),
            startdate: $('#startdate').val(),
            enddate: $('#enddate').val(),
            websiteurl: $('#websiteurl').val(),
            websitelogin: $('#websitelogin').val(),
            websitepassword: $('#websitepassword').val(),
            monthlyamount: $('#monthlyamount').val(),
            gbplinkandaccess1: finalgbp1,
            gbplinkandaccess2: finalgbp2,
            gbplinkandaccess3: finalgbp3,
            gbplinkandaccess4: finalgbp4,
            gbplinkandaccess5: finalgbp5,
            registrarurl: $('#registrarurl').val(),
            registrarusername: $('#registrarusername').val(),
            registrarpassword: $('#registrarpassword').val(),
            googleanalyticsaccess: $('#googleanalyticsaccess').val(),
            googleanalyticsusername: $('#googleanalyticsusername').val(),
            googleanalyticspassword: $('#googleanalyticspassword').val(),
            searchconsoleaccess: $('#searchconsoleaccess').val(),
            searchconsoleusername: $('#searchconsoleusername').val(),
            searchconsolepassword: $('#searchconsolepassword').val(),
            hosturl: $('#hosturl').val(),
            hostusername: $('#hostusername').val(),
            hostpassword: $('#hostpassword').val(),
            bldsubmitted: $('#bldsubmitted').val(),
            bldcsvurl1: $('#bldcsvurl1').val(),
            bldcsvurl2: $('#bldcsvurl2').val(),
            bldcsvurl3: $('#bldcsvurl3').val(),
            bldcsvurl4: $('#bldcsvurl4').val(),
            bldcsvurl5: $('#bldcsvurl5').val(),
            periodcomplete: $('#periodcomplete').val(),
            servicesdescription: $('#servicesdescription').val(),
        };







        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {

            console.log(response);


        });
    });

    // Editing an existing Logo Project.
    $(document).on('click', '.cm-edit-service-logo-service', function() {

        // Let's get the Client ID
        var value = $('#cm-edit-services-select-a-client').val();
        var parts = value.split('-');
        for (var i = 0; i < parts.length; i++) {
            parts[i] = parts[i].trim();
        }
        var business_name = parts[0];

        var clientid = $(this).attr('data-clientid');
        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select, textarea').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        var data = {};

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

        data['action'] = 'cm_service_logo_edit';
        data['nonce'] = customAjax.nonce;
        data['clientid'] = clientid;
        data['business_name'] = business_name;

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {

            if ( 'success' === response ) {
                alert('Successfully edited a hosting service!');
            } else {
                console.log(response);
            }

        });
    });

    // Editing an existing Website Maintenance entry...
    $(document).on('click', '.cm-edit-service-website-maintenance-service', function() {

        var maintenanceid = $(this).attr('data-maintenanceid');
        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        var data = {};

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

        data['action'] = 'cm_service_maintenance_edit';
        data['nonce'] = customAjax.nonce;
        data['maintenanceid'] = maintenanceid;

        console.log(data);
        console.log('yo');

        $.post(customAjax.ajax_url, data, function(response) {

            console.log('in the post');

            if ( 'success' === response ) {
                alert('Successfully edited a maintenance service!');
            } else {
                console.log(response);
            }

        });
    });




    // Populating new Services form options upon choosing a Client - WEBSITE PROJECT
    $('#cm-new-project-select-a-client').on('change', function() {

        // Let's get the Client ID
        var value = $('#cm-new-project-select-a-client').val();
        var parts = value.split('-');
        for (var i = 0; i < parts.length; i++) {
            parts[i] = parts[i].trim();
        }
        var clientid = parts.length > 1 ? parts[1] : '';

        if ('Website Design & Development' === $('#cm-new-project-select-a-project-type').val()) {
            var data = {
                action: 'cm_new_project_get_creative_brief',
                nonce: customAjax.nonce,
                client_id: clientid,
            };

            $.post(customAjax.ajax_url, data, function(response) {
                if (response && response.length > 0) {
                    var optionHtml = '';
                    response.forEach(function(client) {
                        var date = new Date(client.project_start_date);
                        var options = { year: 'numeric', month: 'long', day: 'numeric' };
                        var formattedDate = date.toLocaleDateString('en-US', options);
                        optionHtml = optionHtml + '<option>Creative Brief ID-' + client.id + '-' + formattedDate + '</option>';
                    });
                    $('#project_creative_brief_id').empty().append(optionHtml);
                } else {
                    optionHtml = '<option>No associated Creative Briefs</option>';
                    $('#project_creative_brief_id').empty().append(optionHtml);
                }
            });
        }
    });

    // Populating the "Edit Services" admin tab after selecting a client - SERVICES
    $('#cm-edit-services-select-a-client').on('change', function() {

        // Let's get the Client ID
        var value = $('#cm-edit-services-select-a-client').val();
        var parts = value.split('-');
        for (var i = 0; i < parts.length; i++) {
            parts[i] = parts[i].trim();
        }
        var clientid = parts.length > 1 ? parts[1] : '';
        console.log(clientid);

        var data = {
            action: 'cm_edit_services',
            nonce: customAjax.nonce,
            client_id: clientid,
        };

        $.post(customAjax.ajax_url, data, function(response) {
           $('.indiv-top-edit-services-form-holder').empty().append(response);
        });           

    });

    // Populating New Project form options upon choosing a Client - WEBSITE PROJECT
    $('#cm-new-project-select-a-client').on('change', function() {

        // Let's get the Client ID
        var value = $('#cm-new-project-select-a-client').val();
        var parts = value.split('-');
        for (var i = 0; i < parts.length; i++) {
            parts[i] = parts[i].trim();
        }
        var clientid = parts.length > 1 ? parts[1] : '';

        if ('Website Design & Development' === $('#cm-new-project-select-a-project-type').val()) {
            var data = {
                action: 'cm_new_project_get_launch_checklist',
                nonce: customAjax.nonce,
                client_id: clientid,
            };

            $.post(customAjax.ajax_url, data, function(response) {
                if (response && response.length > 0) {
                    var optionHtml = '';
                    response.forEach(function(client) {
                        var date = new Date(client.project_start_date);
                        var options = { year: 'numeric', month: 'long', day: 'numeric' };
                        var formattedDate = date.toLocaleDateString('en-US', options);
                        optionHtml = optionHtml + '<option>Checklist ID-' + client.id + '-' + formattedDate + '</option>';
                    });
                    $('#project_launch_checklist_id').empty().append(optionHtml);
                } else {
                    optionHtml = '<option>No associated Website Launch Checklists</option>';
                    $('#project_launch_checklist_id').empty().append(optionHtml);
                }
            });
        }
    });

    // Revealing project form(s) based on project/service type selection in drop-down.
    $('#cm-new-project-select-a-project-type').on('change', function() {

        $('.cm-new-project-form-actual-top-holder').css({ 'opacity': '0', 'pointer-events': 'none', 'z-index': 'initial' });

        if ('Website Design & Development' === $(this).val()) {
            $('.cm-website-design-development-form').css({ 'opacity': '1', 'pointer-events': 'all', 'z-index': '9999' });
        }

        if ('Website Hosting' === $(this).val()) {
            $('.cm-website-hosting-form').css({ 'opacity': '1', 'pointer-events': 'all', 'z-index': '9999' });
        }

        if ('Website Maintenance & Support' === $(this).val()) {
            $('.cm-website-maintenance-form').css({ 'opacity': '1', 'pointer-events': 'all', 'z-index': '9999' });
        }

        if ('Logo' === $(this).val()) {
            $('.cm-logo-form').css({ 'opacity': '1', 'pointer-events': 'all', 'z-index': '9999' });
        }

        if ('SEO Related Services' === $(this).val()) {
            $('.cm-seo-services-form').css({ 'opacity': '1', 'pointer-events': 'all', 'z-index': '9999' });
        }
    });

    // Saving a New Service - WEBSITE PROJECT
    $('.cm-create-new-website-service').on('click', function() {

        var tempclientid = $('#cm-new-project-select-a-client').val();
        if ( null === tempclientid ){
            alert('You forgot to select a Client!');
            return false;
        }

        var clientid = tempclientid.split('-')[1].trim();
        var business_name = tempclientid.split('-')[0].trim();
        var data = {
            action: 'cm_service_website_create',
            nonce: customAjax.nonce,
            client_id: clientid,
            business_name: business_name,
            project_start_date: $('#project_start_date').val(),
            service_unique_name: $('#service_unique_name').val(),
            project_completion_date: $('#project_completion_date').val(),
            project_launch_date: $('#project_launch_date').val(),
            project_status: $('#project_status').val(),
            project_url: $('#project_url').val(),
            project_dev_url: $('#project_dev_url').val(),
            project_total_investment: $('#project_total_investment').val(),
            project_host: $('#project_host').val(),
            project_host_url: $('#project_host_url').val(),
            project_host_username: $('#project_host_username').val(),
            project_host_password: $('#project_host_password').val(),
            project_domain_responsibility: $('#project_domain_responsibility').val(),
            project_domain_registrar_url: $('#project_domain_registrar_url').val(),
            project_domain_registrar_username: $('#project_domain_registrar_username').val(),
            project_domain_registrar_password: $('#project_domain_registrar_password').val(),
            project_homepage_approval: $('#project_homepage_approval').val(),
            project_full_site_approval: $('#project_full_site_approval').val(),
            project_google_analytics_access: $('#project_google_analytics_access').val(),
            project_google_analytics_username: $('#project_google_analytics_username').val(),
            project_google_analytics_password: $('#project_google_analytics_password').val(),
            project_search_console_access: $('#project_search_console_access').val(),
            project_search_console_username: $('#project_search_console_username').val(),
            project_search_console_password: $('#project_search_console_password').val(),
        };

        console.log('Below is the output from the new website project form');
        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {

                alert('Successfully created a new website project!');
            	var websiteProjectId = response.data; // Accessing the $websiteprojectid
                console.log('Project ID: ' + websiteProjectId);

                // Now let's save a new Creative brief entry
                var cbdata = {
                    action: 'cm_service_website_creative_brief_create',
                    nonce: customAjax.nonce,
                    client_id: clientid,
                    business_name: business_name,
                    website_project_id: websiteProjectId,
                    project_start_date: $('#project_start_date').val(),
                    service_unique_name: $('#service_unique_name').val(),
                    generaldescription: $('#generaldescription').val(),
                    differentiators: $('#differentiators').val(),
                    awardsandcerts: $('#awardsandcerts').val(),
                    competitorinfo: $('#competitorinfo').val(),
                    competitorurl1: $('#competitorurl1').val(),
                    competitorurl2: $('#competitorurl2').val(),
                    competitorurl3: $('#competitorurl3').val(),
                    services: $('#services').val(),
                    logobrandbookurl: $('#logobrandbookurl').val(),
                    logobrandbooknotes: $('#logobrandbooknotes').val(),
                    colornotes: $('#colornotes').val(),
                    fontnotes: $('#fontnotes').val(),
                    taglinesmottos: $('#taglinesmottos').val(),
                    inspowebsiteurl1: $('#inspowebsiteurl1').val(),
                    inspowebsiteurl2: $('#inspowebsiteurl2').val(),
                    inspowebsiteurl3: $('#inspowebsiteurl3').val(),
                    generaldesignnotes: $('#generaldesignnotes').val(),
                    targetaudiencenotes: $('#targetaudiencenotes').val(),
                    currentwebsiteurl: $('#currentwebsiteurl').val(),
                    currentwebsitelogin: $('#currentwebsitelogin').val(),
                    currentwebsitepassword: $('#currentwebsitepassword').val(),
                    currenthostingurl: $('#currenthostingurl').val(),
                    currenthostinglogin: $('#currenthostinglogin').val(),
                    currenthostingpassword: $('#currenthostingpassword').val(),
                    currentdomainurl: $('#currentdomainurl').val(),
                    currentdomainlogin: $('#currentdomainlogin').val(),
                    currentdomainpassword: $('#currentdomainpassword').val(),
                    socialfacebooklink: $('#socialfacebooklink').val(),
                    sociallinkedinlink: $('#sociallinkedinlink').val(),
                    socialinstagramlink: $('#socialinstagramlink').val(),
                    socialyoutubelink: $('#socialyoutubelink').val(),
                    socialtwitterlink: $('#socialtwitterlink').val(),
                    socialtiktoklink: $('#socialtiktoklink').val(),
                    socialpinterestlink: $('#socialpinterestlink').val(),
                    lastthoughts: $('#lastthoughts').val(),
                };
                console.log('Below is the output from the Creative Brief form');
                console.log(cbdata);

                $.post(customAjax.ajax_url, cbdata, function(response) {
                    if (response.success) {
                        alert(response.data.message);

                        // Now let's save a new Launch Checklist Entry
                        var values = [];
                        $('.cm-indiv-subtitle-inner-launch-checklist-form select').each(function() {
                            var value = $(this).val();
                            switch(value) {
                                case "Not Completed":
                                    values.push(0);
                                    break;
                                case "Completed":
                                    values.push(1);
                                    break;
                                case "In Progress":
                                    values.push(2);
                                    break;
                                case "N/A":
                                    values.push(3);
                                    break;
                            }
                        });
                        var resultString = values.join(',');
                        console.log('Below is the output from the Launch checklist form');
                        console.log(resultString);

                        var lcdata = {
                            action: 'cm_service_website_launch_checklist_create',
                            nonce: customAjax.nonce,
                            client_id: clientid,
                            business_name: business_name,
                            checklist_string: resultString,
                            website_project_id: websiteProjectId,
                        };

                        $.post(customAjax.ajax_url, lcdata, function(response) {
                            console.log('Response from website launch checklist creation: ' + response);
                            if (response.success) {
                                alert(response.data.message);
                            } else {
                                alert('An error occurred while adding the client\'s Launch Checklist.');
                            }
                        });
                    } else {
                        alert('An error occurred while adding the client.');
                    }
                });
            } else {
                alert('An error occurred while creating a new website project');
            }
        });
    });

    // Saving a New Service - WEBSITE HOSTING
    $('.cm-create-new-hosting-service').on('click', function() {

        var tempclientid = $('#cm-new-project-select-a-client').val();
        var clientid = tempclientid.split('-')[1].trim();
        var business_name = tempclientid.split('-')[0].trim();

        var data = {
            action: 'cm_service_website_hosting_create',
            nonce: customAjax.nonce,
            client_id: clientid,
            business_name: business_name,
            hosting_start_date: $('#hosting_start_date').val(),
            hosting_end_date: $('#hosting_end_date').val(),
            hosting_website_url: $('#hosting_website_url').val(),
            hosting_monthly_investment: $('#hosting_monthly_investment').val(),
            hosting_total_investment: $('#hosting_total_investment').val(),
            hosting_host: $('#hosting_host').val(),
            hosting_host_url: $('#hosting_host_url').val(),
            hosting_host_username: $('#hosting_host_username').val(),
            hosting_host_password: $('#hosting_host_password').val(),
            hosting_domain_responsibility: $('#hosting_domain_responsibility').val(),
            hosting_domain_registrar_url: $('#hosting_domain_registrar_url').val(),
            hosting_domain_registrar_username: $('#hosting_domain_registrar_username').val(),
            hosting_domain_registrar_password: $('#hosting_domain_registrar_password').val(),
            hosting_site_files_link: $('#hosting_site_files_link').val(),
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Saving a New Service - SEO SERVICE
    $('.cm-create-new-seo-related-service').on('click', function() {

        var tempclientid = $('#cm-new-project-select-a-client').val();

        if( ( null == tempclientid ) || ( 'null' == tempclientid )  ){
            alert('Choose a client to add this service to.');
        }
        
        var clientid = tempclientid.split('-')[1].trim();

        var gbplink1 = $(this).parent().find('#gbp1link').val();
        var gbpaccess1 = $(this).parent().find('#gbp1access').val();
        var finalgbp1 = gbplink1 + ',' + gbpaccess1;

        var gbplink2 = $(this).parent().find('#gbp2link').val()
        var gbpaccess2 = $(this).parent().find('#gbp2access').val();
        var finalgbp2 = gbplink2 + ',' + gbpaccess2;

        var gbplink3 = $(this).parent().find('#gbp3link').val()
        var gbpaccess3 = $(this).parent().find('#gbp3access').val();
        var finalgbp3 = gbplink3 + ',' + gbpaccess3;

        var gbplink4 = $(this).parent().find('#gbp4link').val()
        var gbpaccess4 = $(this).parent().find('#gbp4access').val();
        var finalgbp4 = gbplink4 + ',' + gbpaccess4;

        var gbplink5 = $(this).parent().find('#gbp5link').val()
        var gbpaccess5 = $(this).parent().find('#gbp5access').val();
        var finalgbp5 = gbplink5 + ',' + gbpaccess5;

        var data = {
            action: 'cm_service_seo_related_create',
            nonce: customAjax.nonce,
            clientid: clientid,
            service_unique_name: $(this).parent().find('#service_unique_name').val(),
            startdate:$(this).parent().find('#startdate').val(),
            enddate:$(this).parent().find('#enddate').val(),
            websiteurl:$(this).parent().find('#websiteurl').val(),
            websitelogin:$(this).parent().find('#websitelogin').val(),
            websitepassword:$(this).parent().find('#websitepassword').val(),
            monthlyamount:$(this).parent().find('#monthlyamount').val(),
            gbplinkandaccess1: finalgbp1,
            gbplinkandaccess2: finalgbp2,
            gbplinkandaccess3: finalgbp3,
            gbplinkandaccess4: finalgbp4,
            gbplinkandaccess5: finalgbp5,
            registrarurl:$(this).parent().find('#registrarurl').val(),
            registrarusername:$(this).parent().find('#registrarusername').val(),
            registrarpassword:$(this).parent().find('#registrarpassword').val(),
            googleanalyticsaccess:$(this).parent().find('#googleanalyticsaccess').val(),
            googleanalyticsusername:$(this).parent().find('#googleanalyticsusername').val(),
            googleanalyticspassword:$(this).parent().find('#googleanalyticspassword').val(),
            searchconsoleaccess:$(this).parent().find('#searchconsoleaccess').val(),
            searchconsoleusername:$(this).parent().find('#searchconsoleusername').val(),
            searchconsolepassword:$(this).parent().find('#searchconsolepassword').val(),
            hosturl:$(this).parent().find('#hosturl').val(),
            hostusername:$(this).parent().find('#hostusername').val(),
            hostpassword:$(this).parent().find('#hostpassword').val(),
            bldsubmitted:$(this).parent().find('#bldsubmitted').val(),
            bldcsvurl1:$(this).parent().find('#bldcsvurl1').val(),
            bldcsvurl2:$(this).parent().find('#bldcsvurl2').val(),
            bldcsvurl3:$(this).parent().find('#bldcsvurl3').val(),
            bldcsvurl4:$(this).parent().find('#bldcsvurl4').val(),
            bldcsvurl5:$(this).parent().find('#bldcsvurl5').val(),
            periodcomplete:$(this).parent().find('#periodcomplete').val(),
            servicesdescription:$(this).parent().find('#servicesdescription').val(),
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Saving a New Service - WEBSITE MAINTENANCE
    $('.cm-create-new-maintenance-service').on('click', function() {

        var tempclientid = $('#cm-new-project-select-a-client').val();
        var clientid = tempclientid.split('-')[1].trim();
        var business_name = tempclientid.split('-')[0].trim();

        var data = {
            action: 'cm_service_website_maintenance_create',
            nonce: customAjax.nonce,
            client_id: clientid,
            business_name: business_name,
            support_start_date: $('#support_start_date').val(),
            support_end_date: $('#support_end_date').val(),
            website_url: $('#website_url').val(),
            plugin_updates: $('#plugin_updates').val(),
            core_file_updates: $('#core_file_updates').val(),
            theme_file_updates: $('#theme_file_updates').val(),
            ssl_cert: $('#ssl_cert').val(),
            support_hours_type: $('#support_hours_type').val(),
            hours_accrue: $('#hours_accrue').val(),
            accrue_limit: $('#accrue_limit').val(),
            hourly_rate: $('#hourly_rate').val(),
            bonus_hours_pool: $('#bonus_hours_pool').val(),
            total_bonus_hours_used: $('#total_bonus_hours_used').val(),
            hours_initially_available: $('#hours_initially_available').val(),
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Saving a New Service - WEBSITE MAINTENANCE
    $('.cm-create-new-logo-service').on('click', function() {

        var tempclientid = $('#cm-new-project-select-a-client').val();
        var clientid = tempclientid.split('-')[1].trim();
        var business_name = tempclientid.split('-')[0].trim();
        var projectuniquename = $('#projectuniquename').val();
        var currentlogourl = $('#currentlogourl').val();
        var draft1url = $('#draft1url').val();
        var draft1colorcodes = $('#draft1colorcodes').val();
        var draft1fonts = $('#draft1fonts').val();
        var draft1notes = $('#draft1notes').val();
        var draft2url = $('#draft2url').val();
        var draft2colorcodes = $('#draft2colorcodes').val();
        var draft2fonts = $('#draft2fonts').val();
        var draft2notes = $('#draft2notes').val();
        var draft3url = $('#draft3url').val();
        var draft3colorcodes = $('#draft3colorcodes').val();
        var draft3fonts = $('#draft3fonts').val();
        var draft3notes = $('#draft3notes').val();
        var finallogourl = $('#finallogourl').val();
        var finallogocolorcodes = $('#finallogocolorcodes').val();
        var finallogofonts = $('#finallogofonts').val();
        var finallogonotes = $('#finallogonotes').val();
        var finallogovarianturl1 = $('#finallogovarianturl1').val();
        var finallogovarianturl2 = $('#finallogovarianturl2').val();
        var finallogovarianturl3 = $('#finallogovarianturl3').val();
        var finalfavicon = $('#finalfavicon').val();
        var zipdownloadurl = $('#zipdownloadurl').val();

        var data = {
            action: 'cm_service_logo_create',
            nonce: customAjax.nonce,
            client_id: clientid,
            projectuniquename: projectuniquename,
            business_name: business_name,
            currentlogourl: currentlogourl,
            draft1url: draft1url,
            draft1colorcodes: draft1colorcodes,
            draft1fonts: draft1fonts,
            draft1notes: draft1notes,
            draft2url: draft2url,
            draft2colorcodes: draft2colorcodes,
            draft2fonts: draft2fonts,
            draft2notes: draft2notes,
            draft3url: draft3url,
            draft3colorcodes: draft3colorcodes,
            draft3fonts: draft3fonts,
            draft3notes: draft3notes,
            finallogourl: finallogourl,
            finallogocolorcodes: finallogocolorcodes,
            finallogofonts: finallogofonts,
            finallogovarianturl1: finallogovarianturl1,
            finallogovarianturl2: finallogovarianturl2,
            finallogovarianturl3: finallogovarianturl3,
            finalfavicon: finalfavicon,
            zipdownloadurl: zipdownloadurl,
            finallogonotes: finallogonotes,
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Saving a New Service - WEBSITE SUPPORT TICKET
    $('.cm-create-new-website-support-ticket').on('click', function() {

        var tempclientid = $('#cm-new-support-ticket-select-a-client').val();
        var clientid = tempclientid.split('-')[1].trim();
        var business_name = tempclientid.split('-')[0].trim();

        var data = {
            action: 'cm_service_website_support_ticket_create',
            nonce: customAjax.nonce,
            clientid: clientid,
            business_name: business_name,
            startdatetime: $('#startdatetime').val(),
            enddatetime: $('#enddatetime').val(),
            status: $('#status').val(),
            websiteurl: $('#websiteurl').val(),
            nocharge: $('#nocharge').val(),
            submitteremail: $('#submitteremail').val(),
            submitterphone: $('#submitterphone').val(),
            initialdescription: $('#initialdescription').val(),
            notes: $('#notes').val(),
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('An error occurred while adding the client.');
            }
        });
    });

    // Toggling client details
    $(document).on('click', '.cm-indiv-client-name', function() {
        console.log('clicked');
        var thingtoexpand = $(this).next();
        if (thingtoexpand.height() === 0) {
            thingtoexpand.css('height', 'auto');
        } else {
            thingtoexpand.css('height', 0);
        }
    });


    // Populating existing support tickets for a particular client
    $('#cm-new-support-ticket-select-a-client').on('change', function() {

        // Let's get the Client ID
        var value = $('#cm-new-support-ticket-select-a-client').val();
        var parts = value.split('-');
        for (var i = 0; i < parts.length; i++) {
            parts[i] = parts[i].trim();
        }
        var clientid = parts.length > 1 ? parts[1] : '';

        var data = {
            action: 'cm_get_existing_support_tickets',
            nonce: customAjax.nonce,
            clientid: clientid,
        };

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {
            $('.indiv-top-edit-services-form-holder').remove();
            $('.indiv-new-project-form-holder').append(response);
        });
    });


    // Editing an existing Support Ticket.
    $(document).on('click', '.cm-edit-indiv-support-ticket', function() {

        var ticketid = $(this).attr('data-ticketid');
        var parent = $(this).parent();

        // Find all input elements within the parent element using jQuery, excluding those within '.cm-indiv-subtitle-inner-form'
        var inputElements = parent.find('input, select, textarea').not('.cm-indiv-subtitle-inner-form input, .cm-indiv-subtitle-inner-form select');

        var data = {};

        // Iterate over each input element and add to the data object
        inputElements.each(function() {
            var key = $(this).attr('id');
            var value = $(this).val();

            if (key) {
                data[key] = value;
            }
        });

        data['action'] = 'cm_support_ticket_edit';
        data['nonce'] = customAjax.nonce;
        data['ticketid'] = ticketid;

        console.log(data);

        $.post(customAjax.ajax_url, data, function(response) {

            if ( 'success' === response ) {
                alert('Successfully edited a support ticket!');
            } else {
                console.log(response);
            }

        });
    });

    function formatTimestampInput(inputId) {
        // Get the input element
        var input = document.getElementById(inputId);
        
        // Add event listener for when input loses focus
        input.addEventListener('blur', function() {
            var value = input.value.trim();
            
            // Check if the input matches expected format
            var regex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
            if (!regex.test(value)) {
                // Display error message or handle invalid input
                alert('Invalid timestamp format. Please enter in YYYY-MM-DD HH:MM:SS format.');
                return;
            }
            
            // Optionally, you can further validate individual components like year, month, etc.
            
            // If input is valid, no need to change anything
        });
    }

    $(document).on('change', '.cm-class-upload-bldcsvurl1, .cm-class-upload-bldcsvurl2, .cm-class-upload-bldcsvurl3, .cm-class-upload-bldcsvurl4, .cm-class-upload-bldcsvurl5', function () {
        console.log('Change event fired!');
        console.log('triggerred');
        var fileInput = $(this)[0];
        var file = fileInput.files[0];

        if (!file) {
            alert('Please select a file.');
            return;
        }

        // Extract the last character from the file input's ID (1,2,3,4,5)
        var inputId = $(this).attr('id');
        var locationNumber = inputId.charAt(inputId.length - 1);

        // Modify file name: Append "-location-X"
        var fileNameParts = file.name.split('.');
        var fileExtension = fileNameParts.pop(); // Get the file extension
        var baseFileName = fileNameParts.join('.'); // Get the name without the extension
        var newFileName = `${baseFileName}-location-${locationNumber}.${fileExtension}`;

        // Get the Client ID
        var value = $('#cm-new-project-select-a-client').val();

        console.log( 'value' );
        console.log( value )

        if ( ( null === value ) || ( 'null' === value ) || ( '' === value ) || ( undefined === value ) || ( 'undefined' === value ) ){
            value = $('#cm-edit-services-select-a-client').val();
        }

        var parts = value.split('-').map(part => part.trim());
        var business_name = parts[0];
        
        var service_unique_name = $(this).parent().parent().find('#service_unique_name').val().trim();

        // Sanitize: Allow only letters, numbers, dashes, and underscores
        service_unique_name = service_unique_name.replace(/[^a-zA-Z0-9-_]/g, '');

        // Ensure it's not empty, otherwise use a fallback name
        if (service_unique_name === '') {
            service_unique_name = 'default_folder';
        }

        // Optional: Limit the length to 50 characters (adjust as needed)
        service_unique_name = service_unique_name.substring(0, 50);

        // Optional: Append a timestamp to ensure uniqueness
        service_unique_name += '_' + Date.now();

        console.log("Sanitized folder name:", service_unique_name);
        console.log( 'service_unique_name' );
        console.log( service_unique_name );

        if (!business_name) {
            alert('Please enter a business name.');
            return;
        }

        console.log("Checking existing file: ", newFileName, " for business: ", business_name);

        // Step 1: Check if file exists and delete it if necessary
        ajaxRequest(
            'check_and_delete_file',
            { file_name: newFileName, business_name: business_name },
            function (response) {
                console.log("Delete response: ", response);

                if (response.deleted || response.not_found) {
                    console.log("Proceeding with file upload...");

                    // Step 2: Proceed with upload
                    var formData = new FormData();
                    formData.append('file', new File([file], newFileName, { type: file.type })); // Rename file here
                    formData.append('file_name', newFileName); // Ensure the filename is sent for reference in PHP
                    formData.append('business_name', business_name);
                    formData.append('service_unique_name', service_unique_name);

                    ajaxRequest(
                        'bld_csv_file_upload',
                        formData,
                        function (uploadResponse) {
                            console.log("Upload response: ", uploadResponse);
                            if (uploadResponse.url) {
                                $(`#bldcsvurl${locationNumber}`).val(uploadResponse.url);
                            } else {
                                alert(uploadResponse.error);
                            }
                        },
                        function (error) {
                            alert('An error occurred during upload: ' + error);
                        },
                        true // This tells ajaxRequest it's a FormData request
                    );


                } else {
                    alert('Error deleting existing file: ' + response.error);
                }
            },
            function (error) {
                alert('An error occurred while checking for existing files: ' + error);
            }
        );
    });
});



