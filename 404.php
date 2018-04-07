<?php

header("HTTP/1.1 404 Not Found");

echo '<h2>お探しのページが見つかりませんでした</h2>';
echo '<p>既にページが削除されてしまったか、URLが間違っているのかも知れません。</p>';
echo '<p><a href='.SITE_URL.'>TOPへ戻る</a></p>';
