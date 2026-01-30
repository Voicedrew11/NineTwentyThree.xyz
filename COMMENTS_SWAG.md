# Comments (PHP) on SWAG

The comments section uses `static/comments.php`, which is copied to `public/` by Hugo. Comments are stored as JSON in `comments_data/` next to the script (created automatically on first post).

## SWAG / PHP

1. **Serve PHP**  
   Ensure your SWAG stack runs PHP (e.g. php-fpm) and that `.php` in the same vhost as your site is handled by PHP. The app root should be your `public/` (or wherever Hugo outputs) so `/comments.php` is served and executed.

2. **Writable `comments_data/`**  
   The script writes to `comments_data/` in the same directory as `comments.php`. That directory must exist and be writable by the PHP process (e.g. `www-data`). If it doesn’t exist, PHP will try to create it; ensure the parent directory is writable.

3. **Sessions**  
   CSRF uses `$_SESSION`. Enable `session.save_path` and ensure PHP can write to it (default is often `/tmp`).

4. **Base URL**  
   Redirects use `/comments.php` and `/comments.php?post=...`. If the site lives in a subpath (e.g. `https://example.com/blog/`), update those paths in `comments.php` to include the subpath.

## Quick checks

- Open `https://your-domain/comments.php?post=second` directly. You should see the comments form and “No comments yet.”
- Post a comment: form submits to `comments.php`, then redirects back; the new comment appears.
- Confirm `comments_data/<slug>.json` exists and is updated after posting.
