-- Replace old domain references
UPDATE wp_9e73ifu2ty_options SET option_value = REPLACE(option_value, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_posts SET post_content = REPLACE(post_content, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_postmeta SET meta_value = REPLACE(meta_value, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_usermeta SET meta_value = REPLACE(meta_value, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_users SET user_email = REPLACE(user_email, 'leveluprichmond', 'levelupmarketers'), user_url = REPLACE(user_url, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_comments SET comment_content = REPLACE(comment_content, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_commentmeta SET meta_value = REPLACE(meta_value, 'leveluprichmond', 'levelupmarketers');
UPDATE wp_9e73ifu2ty_links SET link_url = REPLACE(link_url, 'leveluprichmond', 'levelupmarketers');
