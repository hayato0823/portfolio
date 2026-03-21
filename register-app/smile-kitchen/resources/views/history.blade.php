<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/sales_history.css">
    <!-- FlatpickrのCSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- FlatpickrのJS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- 日本語にする場合は下記を追記 --> 
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_orange.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-販売履歴管理</title>
</head>
<body>
    <x-hamburger />
    <header>
        販売履歴管理
    </header>
    <main class="main-content">
        <div class="flex-box">
            <div class="date-box">
                <img src="../images/calendar.png" class="date-icon" alt="icon">
                <input type="text" id="datepicker" name="calendar" placeholder="yyyy/mm/dd" class="date-input">
            </div>
            <input type="search" id="idInput" class="search-form" name="search" placeholder="IDを入力">
            <button type="button" class="search-btn" onclick="search(document.getElementById('idInput').value)">検索</button>
        </div>
        <table class="sticky_table">
        <thead>
            <tr>
                <th>ID</th>  
                <th>商品名</th>
                <th>個数</th>
                <th>合計</th>
                <th>操作</th>
            </tr>
        </thead id="scrollable-tbody">
        <tbody>
            <!--ここに差し込む-->
        </tbody>
        </table>
    </main>

<!--JavaScript-->
<script>
flatpickr("#datepicker", {
    locale: "ja",
    dateFormat: "Y-m-d",
    minDate: "2020-01-01",
    position: "below",
    disableMobile: "true"
});

// コンテナ全体をクリックでカレンダーを開く
document.querySelector(".date-box").addEventListener("click", () => {
    document.querySelector("#datepicker").focus();
});

</script>
<script src="/js/history.js"></script>
<script src="js/redirect.js"></script>
</body>
</html>