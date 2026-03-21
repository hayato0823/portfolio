<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/select.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-データ管理</title>
</head>
<body>
    <x-hamburger />
    <div class="button-grid">
        <button type="button" value="management" onclick="redirect(this)" class="select-btn">    
            <span>売上管理</span>
        </button>
        <button type="button" value="history" onclick="redirect(this)" class="select-btn">
            <span>販売履歴管理</span>
        </button>
    </div>
    <script src="js/redirect.js"></script>
</body>
</html>