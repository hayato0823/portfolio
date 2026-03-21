<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SmilePOS-売上管理</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/Chart.css">
    <link rel="stylesheet" href="../css/sales_history.css">
    <link rel="stylesheet" href="../css/history.css">
    <!-- FlatpickrのCSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- FlatpickrのJS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- 日本語にする場合は下記を追記 --> 
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_orange.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/chart.js@4.4.1/dist/chart.umd.js"></script>
</head>
<body>
    <header>
        売上管理
    </header>
    <x-hamburger />
    <main class="main-content">
        <input type="checkbox" id="popup">
        <!-- ポップアップ -->
        <div class="popup-overlay">
            <div class="popup-window">
                <!-- ポップアップ中身（閉じる）-->
                <label class="popup-close" for="popup">≪ 閉じる</label>
                <div class="chart-wrap">
                    <div id="chartArea">
                        <canvas id="itemChart"></canvas><!-- グラフ挿入位置 -->
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed-area">
            <div class="flex-box">
                <div class="date-box">
                    <img src="../images/calendar.png" class="date-icon" alt="icon">
                    <input type="text" id="datepicker" name="calendar" placeholder="yyyy/mm/dd" class="date-input" onchange="dayget()">
                </div>
            <label class="popup-open" for="popup">
                <img src="images/Chart_icon.png">
            </label>
            </div>

            <table id="salesTable">
                <thead>
                <tr>
                    <th>総売上</th>
                    <th>現計合計</th>
                    <th>クレジット合計</th>
                    <th>総取引数</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="allSale">0</td>
                        <td id="allCash">0</td>
                        <td id="allCredit">0</td>
                        <td id="allTransaction">0</td>
                    </tr>
                </tbody>
            </table>
            <table class="item-list-table">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>販売個数</th>
                        <th>商品別売上</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="scroll-area">
            <table class="item-list-table">

                <tbody id="itemTable">
                <!-- ここにjsで商品ごとの情報を差し込んでる -->
                </tbody>
            </table>
        </div>
    </main>

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

<script src="js/redirect.js"></script>
<script src="js/management.js"></script>
</body>
</html>
