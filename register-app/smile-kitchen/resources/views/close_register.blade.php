<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/partition.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/regi_check.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-レジ締め</title>
</head>
<body class="partition-page">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @csrf
    <header>
        レジ締め
    </header>
    <x-hamburger />
    <main class="partition">
        <!-- 左画面 -->
        <div class="partition-left">
            <div class="partition-left__inner">
                <table>
                    <tbody>
                        <tr>
                            <td class="money-choices">￥10,000</td>
                            <td class="plural"></td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥5,000</td>
                            <td class="plural"></td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥2,000</td>
                            <td class="plural"></td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥1,000</td>
                            <td class="plural"></td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥500</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥100</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥50</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥10</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥5</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                        <tr>
                            <td class="money-choices">￥1</td>
                            <td class="plural"><input class="input-plural" type="number" value="0"> 本</td>
                            <td class="singular"><input class="input-singular" type="number" value="0"> 枚</td>
                            <td class="typeToatal">¥ 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 右画面 -->
        <div class="partition-right">
            <div class="partition-right__inner">
                <div class="result-box">
                    <div class="result-title">
                        <span>実ドロア在高</span>
                        <div class="result-number">
                            <span>￥</span>
                            <span>0</span>
                        </div>
                    </div>
                    <div class="result-title">
                        <span>理論現金在高</span>
                        <div class="result-number">
                            <span>￥</span>
                            <span>{{ $expected_cash }}</span>
                        </div>
                    </div>
                    <div class="result-title">
                        <span>差異</span>
                        <div class="difference-number">
                            <span>￥</span>
                            <span>{{ $difference }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex-box-btn">
                    <button class="confirm-btn">OK</button>
                    <button class="reset-btn">リセット</button>
                </div>
            </div>

        </div>
    </main>
    <script src="/js/close_register.js"></script>
    <script src="js/redirect.js"></script>
</body>
</html>