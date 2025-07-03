-- SQL script to replace all occurrences of 'leveluprichmond' with 'levelupmarketers'
-- while keeping local development URLs intact.
-- Run in phpMyAdmin or MySQL after importing local.sql.

SET @search = 'leveluprichmond';
SET @replace = 'levelupmarketers';

-- wp_options (skip siteurl and home)
UPDATE `wp_9e73ifu2ty_options`
SET `option_value` = REPLACE(`option_value`, @search, @replace)
WHERE `option_name` NOT IN ('siteurl','home')
  AND `option_value` LIKE CONCAT('%', @search, '%');

-- wp_posts content and excerpts
UPDATE `wp_9e73ifu2ty_posts`
SET `post_content` = REPLACE(`post_content`, @search, @replace)
WHERE `post_content` LIKE CONCAT('%', @search, '%');

UPDATE `wp_9e73ifu2ty_posts`
SET `post_excerpt` = REPLACE(`post_excerpt`, @search, @replace)
WHERE `post_excerpt` LIKE CONCAT('%', @search, '%');

-- wp_posts GUIDs
UPDATE `wp_9e73ifu2ty_posts`
SET `guid` = REPLACE(`guid`, @search, @replace)
WHERE `guid` LIKE CONCAT('%', @search, '%');

-- wp_postmeta values
UPDATE `wp_9e73ifu2ty_postmeta`
SET `meta_value` = REPLACE(`meta_value`, @search, @replace)
WHERE `meta_value` LIKE CONCAT('%', @search, '%');

-- user meta
UPDATE `wp_9e73ifu2ty_usermeta`
SET `meta_value` = REPLACE(`meta_value`, @search, @replace)
WHERE `meta_value` LIKE CONCAT('%', @search, '%');

-- users table (email and url)
UPDATE `wp_9e73ifu2ty_users`
SET `user_email` = REPLACE(`user_email`, @search, @replace)
WHERE `user_email` LIKE CONCAT('%', @search, '%');

UPDATE `wp_9e73ifu2ty_users`
SET `user_url` = REPLACE(`user_url`, @search, @replace)
WHERE `user_url` LIKE CONCAT('%', @search, '%');

-- comments
UPDATE `wp_9e73ifu2ty_comments`
SET `comment_content` = REPLACE(`comment_content`, @search, @replace)
WHERE `comment_content` LIKE CONCAT('%', @search, '%');

UPDATE `wp_9e73ifu2ty_comments`
SET `comment_author_email` = REPLACE(`comment_author_email`, @search, @replace)
WHERE `comment_author_email` LIKE CONCAT('%', @search, '%');
