<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/select.css">
    <link rel="stylesheet" href="../css/disabled_btn.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-レジ開け／締め</title>
</head>
<body>
    <x-hamburger />
    <div class="button-grid">
        <button type="button" value="open_register" onclick="redirect(this)" class="select-btn" @disabled($record)><!--レジ開け後はグレーアウト -->
            <span>開ける</span>
        </button>
        <button type="button" value="close_register" onclick="redirect(this)" class="select-btn" @disabled(!$record)><!--販売前はグレーアウト -->
            <span>締める</span>
        </button>
    </div>
    <script src="js/redirect.js"></script>
</body>
</html>