<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ハンバーガーメニュー</title>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        /* --- ハンバーガーボタン（メニューの外に出す） --- */
        .hamburger {
            position: fixed;
            /* 以前の scale(2.0) の代わりに直接大きさを指定 */
            top: 20px;
            right: 20px;
            width: 60px;  /* 30px * 2 */
            height: 60px; /* タップ領域確保 */
            
            cursor: pointer;
            z-index: 200; /* 最前面に（ヘッダーより上） */
            
            /* ボタンの中身を中央揃え */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            
            /* タップ時のハイライト消去 */
            -webkit-tap-highlight-color: transparent;
        }

        .hamburger span {
            display: block;
            height: 4px;      /* 線を少し太く */
            width: 50px;      /* 幅を広げる */
            background-color: #333;
            margin: 6px 0;    /* 間隔を広げる */
            transition: 0.4s;
            border-radius: 2px;
        }

        /* バツ印のアニメーション */
        .hamburger.active span:nth-child(1) {
            transform: translateY(16px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-16px) rotate(-45deg);
        }


        /* --- メニュー本体 --- */
        .menu {
            position: fixed;
            top: 0;
            right: -120%; /* 確実に隠す */
            
            width: 100%;       /* スマホなら幅いっぱい、タブレットなら調整 */
            max-width: 400px;  /* 最大幅制限 */
            
            height: 100%;
            background-color: #fafad2;
            color: #333;
            transition: right 0.3s ease;
            
            /* ボタンと被らないように上余白を大きくとる */
            padding: 100px 30px 30px 30px; 
            box-sizing: border-box;
            
            z-index: 150; /* ボタン(200)より下、ヘッダー(100)より上 */
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        }

        /* メニューが開いているとき */
        .menu.open {
            right: 0;
        }

        /* --- メニューリスト --- */
        .menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            /* scale は使わず font-size で大きくする */
        }

        .menu li {
            padding: 0;
            border-bottom: 1px solid #ccc;
        }

        .menu li a {
            color: #333;
            text-decoration: none;
            display: block;
            /* 指で押しやすいサイズ */
            padding: 25px 10px;
            font-size: 1.5rem; /* 文字サイズを大きく */
            font-weight: normal;
        }

        /* 無効化されたリンク */
        .menu li a.disabled {
            color: #ccc;
            pointer-events: none;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    @php
        // コントローラーから変数が渡されていない場合（HomeController以外）はここで取得
        if (!isset($recordExists) || !isset($sales) || !isset($actual_cash)) {
            $today = \Illuminate\Support\Carbon::today('Asia/Tokyo');
            $record = \App\Models\Register_reconciliation::whereDate('created_at', '=', $today)->first();
            
            $recordExists = !is_null($record);
            $actual_cash = $record?->actual_cash ?? 0;
            $sales = $record?->sale ?? 0;
        }
    @endphp

    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <nav class="menu">
        <ul>
            <li><a href="#" value="home" onclick="redirect(this)">ホーム</a></li>
            <li><a href="#" value="regi" onclick="redirect(this)" class="{{ !$recordExists ? 'disabled' : '' }}">会計業務</a></li>
            <li><a href="#" value="add_product" onclick="redirect(this)">商品登録</a></li>
            <li><a href="#" value="open_close" onclick="redirect(this)" class="{{ ($recordExists && $sales == 0) || $actual_cash > 0 ? 'disabled' : '' }}">レジ開け/締め</a></li>
            <li><a href="#" value="history_management" onclick="redirect(this)">データ管理</a></li>
        </ul>
    </nav>

    <script>
        // ハンバーガーボタンの開閉処理
        $(function() {
            $('.hamburger').click(function() {
                // メニューの開閉状態を切り替える
                $('.menu').toggleClass('open');
                // ボタンのアニメーションを切り替える
                $(this).toggleClass('active');
            });

            // メニュー外（背景など）をクリックしたら閉じる処理を追加しておくと親切です
            // 必要なければ削除してください
            /*
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.menu').length && !$(e.target).closest('.hamburger').length) {
                    $('.menu').removeClass('open');
                    $('.hamburger').removeClass('active');
                }
            });
            */
        });

        // 画面遷移用ダミー関数（実装済みのredirect.jsがある場合は不要）
        /*
        function redirect(element) {
            const val = element.getAttribute('value');
            console.log("Redirect to: " + val);
            // location.href = ... 実際の処理
        }
        */
    </script>

</body>
</html>