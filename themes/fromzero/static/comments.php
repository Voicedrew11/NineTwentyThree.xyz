<?php
/**
 * Comments section for Hugo posts. Place in static/; ensure comments_data/ is writable.
 * GET ?post=slug — display form + comments. POST — save comment, redirect back.
 */

$DATA_DIR = __DIR__ . '/comments_data';

// #region agent log
$DBG_LOG = '/home/voicedrew/Desktop/nine23/.cursor/debug.log';
function dbg($h, $loc, $msg, $data = []) {
    global $DBG_LOG;
    $e = json_encode(['hypothesisId' => $h, 'location' => $loc, 'message' => $msg, 'data' => $data, 'timestamp' => (int)(microtime(true)*1000), 'sessionId' => 'debug-session']) . "\n";
    @file_put_contents($DBG_LOG, $e, FILE_APPEND | LOCK_EX);
}
// #endregion

function sanitize($s) {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

function slug_ok($s) {
    return preg_match('/^[a-zA-Z0-9_-]+$/', $s ?? '') === 1;
}

function ensure_data_dir($dir) {
    // #region agent log
    dbg('C', 'ensure_data_dir:entry', 'ensure_data_dir', ['dir' => $dir, 'is_dir' => is_dir($dir)]);
    // #endregion
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        // #region agent log
        dbg('C', 'ensure_data_dir:after_mkdir', 'after mkdir', ['dir' => $dir, 'is_dir_now' => is_dir($dir)]);
        // #endregion
    }
}

function get_comments_path($dir, $post) {
    return $dir . '/' . $post . '.json';
}

function load_comments($path) {
    if (!is_file($path)) return [];
    $raw = file_get_contents($path);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function save_comments($path, $comments) {
    ensure_data_dir(dirname($path));
    // #region agent log
    dbg('C', 'save_comments:before_write', 'before file_put_contents', ['path' => $path, 'dir_writable' => is_writable(dirname($path))]);
    // #endregion
    file_put_contents($path, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

function csrf_field() {
    $t = bin2hex(random_bytes(16));
    $_SESSION['comments_csrf'] = $t;
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars($t) . '">';
}

function csrf_ok() {
    return isset($_POST['csrf'], $_SESSION['comments_csrf']) && hash_equals($_SESSION['comments_csrf'], $_POST['csrf']);
}

$post = isset($_REQUEST['post']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_REQUEST['post']) : null;
$posted = isset($_GET['posted']);

// #region agent log
dbg('A', 'comments.php:request', 'request start', ['method' => $_SERVER['REQUEST_METHOD'] ?? '?', 'post' => $post, 'has_author' => isset($_POST['author']), 'has_comment' => isset($_POST['comment']), 'has_csrf' => isset($_POST['csrf']), 'session_status' => session_status()]);
// #endregion

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// #region agent log
dbg('A', 'comments.php:after_session_start', 'after session_start', ['session_status' => session_status(), 'has_csrf_in_session' => isset($_SESSION['comments_csrf'])]);
// #endregion

if (!$post || !slug_ok($post)) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Comments</title></head><body style="color:#fff;font-family:sans-serif;background:#111;padding:1rem;">';
    echo '<p>Missing or invalid post.</p></body></html>';
    exit;
}

$path = get_comments_path($DATA_DIR, $post);
$comments = load_comments($path);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // #region agent log
    $csrf_ok = csrf_ok();
    dbg('B', 'comments.php:csrf_check', 'csrf_ok result', ['csrf_ok' => $csrf_ok]);
    // #endregion
    if (!$csrf_ok) {
        header('Location: /comments.php?post=' . urlencode($post) . '&err=csrf');
        exit;
    }
    $author = isset($_POST['author']) ? trim((string) $_POST['author']) : '';
    $body = isset($_POST['comment']) ? trim((string) $_POST['comment']) : '';
    if ($author !== '' && $body !== '') {
        // #region agent log
        dbg('E', 'comments.php:before_mb', 'before substr', ['author_len' => strlen($author), 'body_len' => strlen($body)]);
        // #endregion
        $author = substr($author, 0, 128);
        $body = substr($body, 0, 4096);
        // #region agent log
        dbg('E', 'comments.php:after_mb', 'after substr', []);
        // #endregion
        $comments[] = [
            'date' => date('c'),
            'author' => $author,
            'comment' => $body,
        ];
        // #region agent log
        $dir = dirname($path);
        dbg('C', 'comments.php:before_save', 'before save_comments', ['path' => $path, 'dir' => $dir, 'dir_exists' => is_dir($dir), 'dir_writable' => is_dir($dir) ? is_writable($dir) : 'n/a']);
        // #endregion
        save_comments($path, $comments);
        // #region agent log
        dbg('C', 'comments.php:after_save', 'after save_comments', ['path' => $path]);
        dbg('D', 'comments.php:before_redirect', 'about to redirect posted=1', []);
        // #endregion
        header('Location: /comments.php?post=' . urlencode($post) . '&posted=1');
        exit;
    }
    header('Location: /comments.php?post=' . urlencode($post) . '&err=empty');
    exit;
}

$err = isset($_GET['err']) ? sanitize($_GET['err']) : '';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Comments — <?php echo sanitize($post); ?></title>
  <style>
    * { box-sizing: border-box; }
    body { color: #fff; font-family: sans-serif; background: transparent; margin: 0; padding: 0.5rem 0; }
    .comments-inner { max-width: 100%; }
    .c-form label { display: block; margin-top: 0.5rem; }
    .c-form input[type="text"], .c-form textarea { width: 100%; max-width: 400px; padding: 0.35rem 0.5rem; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.25); border-radius: 4px; color: #fff; }
    .c-form textarea { min-height: 80px; resize: vertical; }
    .c-form button { margin-top: 0.5rem; padding: 0.4rem 0.75rem; background: #756aab; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    .c-form button:hover { opacity: 0.9; }
    .c-msg { margin-bottom: 0.75rem; padding: 0.35rem 0.5rem; background: rgba(117,106,171,.2); border-radius: 4px; font-size: 0.9rem; }
    .c-list { margin: 0 0 1.5rem; }
    .c-empty { font-size: 0.9rem; opacity: 0.7; margin: 0 0 1.5rem; }
    .c-item { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,.15); }
    .c-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .c-meta { font-size: 0.85rem; opacity: 0.8; margin-bottom: 0.25rem; }
    .c-body { white-space: pre-wrap; word-break: break-word; }
  </style>
</head>
<body>
  <div class="comments-inner">
    <?php if ($posted): ?>
      <p class="c-msg">Thanks, your comment has been saved.</p>
    <?php endif; ?>
    <?php if ($err === 'csrf'): ?>
      <p class="c-msg">Invalid request. Please try again.</p>
    <?php elseif ($err === 'empty'): ?>
      <p class="c-msg">Name and comment are required.</p>
    <?php endif; ?>

    <div class="c-list">
      <?php foreach (array_reverse($comments) as $c): ?>
        <div class="c-item">
          <div class="c-meta"><?php echo sanitize($c['author']); ?> — <?php echo sanitize(date('j M Y, G:i', strtotime($c['date']))); ?></div>
          <div class="c-body"><?php echo nl2br(sanitize($c['comment'])); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (empty($comments)): ?>
      <p class="c-empty">No comments yet.</p>
    <?php endif; ?>

    <form class="c-form" method="post" action="<?php echo htmlspecialchars('/comments.php'); ?>" autocomplete="off">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="post" value="<?php echo sanitize($post); ?>">
      <label for="c-author">Name</label>
      <input type="text" id="c-author" name="author" required maxlength="128" placeholder="Your name" autocomplete="off">
      <label for="c-comment">Comment</label>
      <textarea id="c-comment" name="comment" required maxlength="4096" placeholder="Your comment" autocomplete="off"></textarea>
      <button type="submit">Post comment</button>
    </form>
  </div>
</body>
</html>
