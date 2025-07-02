-- Reset WordPress URL options for the local environment
UPDATE wp_9e73ifu2ty_options
SET option_value = 'https://level-up-digital-marketing.local'
WHERE option_name IN ('siteurl','home');
