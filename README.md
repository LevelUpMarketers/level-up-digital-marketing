# Level Up Digital Marketing


## Database update script

To update references from the old `leveluprichmond` domain to `levelupmarketers`, run the SQL in `DB Uploads/update_domain.sql` using phpMyAdmin or a similar tool. The script updates post content and email addresses while leaving the `siteurl` and `home` options untouched so your local environment won't redirect. If you need to change those options, run an UPDATE query manually.

The `DB Uploads/reset_local_urls.sql` file restores those options to the local domain:

```sql
UPDATE wp_9e73ifu2ty_options
SET option_value = 'https://level-up-digital-marketing.local'
WHERE option_name IN ('siteurl','home');
```
