<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/disabled_btn.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-ホーム</title>
</head>
<body>
    <div class="flex-container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <span>ログアウト</span>
            </button>
        </form>
    </div>
    <div class="button-grid">
        <button type="button" value="regi" onclick="redirect(this)" class="home-btn" id="regi-btn"  @disabled(!$recordExists)><!--レジ開け前はグレーアウト -->
            <img src="../images/regi-icon.png" alt="会計業務">
            <span>会計業務</span>
        </button>
        <button type="button" value="add_product" onclick="redirect(this)" class="home-btn" id="pro-btn">
            <img src="../images/pro-icon.png" alt="商品登録">
            <span>商品登録</span>
        </button>
        <button type="button" value="open_close" onclick="redirect(this)" class="home-btn" id="op-cl-btn" @disabled($recordExists && $sales == 0|| $actual_cash > 0)><!--レジ締後はグレーアウト -->
            <img src="../images/op-cl-icon.png" alt="レジ開け/締め">
            <span>レジ<br>開け／締め</span>
        </button>
        <button type="button" value="history_management" onclick="redirect(this)" class="home-btn" id="data-btn">
            <img src="../images/data-icon.png" alt="データ管理">
            <span>データ管理</span>
        </button>
    </div>
    @if (session('error'))
    <div class="aleart" id="{{ session('error') }}"></div>
    <script src="js/aleart.js"></script><!--アラート出す-->    
    @endif
    <script src="js/redirect.js"></script>   
</body>
</html>




