-- Safely update domain references without breaking serialized data
-- NOTE: We no longer update the 'siteurl' and 'home' options here to avoid
-- unintentionally redirecting a local environment to the live domain.
-- If you need to change those values, run the following manually:
-- UPDATE wp_9e73ifu2ty_options SET option_value = 'https://levelupmarketers.com' WHERE option_name IN ('siteurl','home');

-- Update administrator email
UPDATE wp_9e73ifu2ty_options
SET option_value = REPLACE(option_value, 'admin@leveluprichmond.com', 'admin@levelupmarketers.com')
WHERE option_name = 'admin_email';

-- Update post content and GUIDs
UPDATE wp_9e73ifu2ty_posts
SET post_content = REPLACE(post_content, 'leveluprichmond', 'levelupmarketers'),
    guid = REPLACE(guid, 'leveluprichmond', 'levelupmarketers');

-- Update user emails and URLs
UPDATE wp_9e73ifu2ty_users
SET user_email = REPLACE(user_email, 'leveluprichmond', 'levelupmarketers'),
    user_url   = REPLACE(user_url, 'leveluprichmond', 'levelupmarketers');

-- Update links
UPDATE wp_9e73ifu2ty_links
SET link_url = REPLACE(link_url, 'leveluprichmond', 'levelupmarketers');

-- Update comment content
UPDATE wp_9e73ifu2ty_comments
SET comment_content = REPLACE(comment_content, 'leveluprichmond', 'levelupmarketers');
