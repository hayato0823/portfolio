<!DOCTYPE html>
<html lang="ja">

    <head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="../css/regi.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/regi_split.css">
        <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
        <title>SmilePOS-会計業務</title>
    </head>

    <body class="split-page">
        <main class="split-64">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <input type="checkbox" id="popup">
            <!-- ポップアップ -->
            <div class="popup-overlay">
            <div class="popup-window">
                <!-- ポップアップ中身（閉じる）-->
                <label class="popup-close" for="popup" onclick="clearDisplay(this)">≪ 戻る</label>
                <!-- ポップアップ中身（電卓部分）-->
                <div id="calculator">
                <div class="calc-row">
                    <div class="calc-label">小計</div>
                    <div class="calc-value" id="subtotal-value">0円</div>
                </div>
                <div class="calc-row-paid">
                    <div class="calc-label">預かり</div>
                    <div class="calc-value" id="paid-value">0円</div>
                </div>
                <div class="calc-row">
                    <div class="calc-label">おつり</div>
                    <div class="calc-value" id="change-value" data-change="0">0円</div>
                </div>
                <table>
                    <tr>
                    <td><button class="square" onclick="appendToDisplay('7')">7</button></td>
                    <td><button class="square" onclick="appendToDisplay('8')">8</button></td>
                    <td><button class="square" onclick="appendToDisplay('9')">9</button></td>
                    <td><button class="red" onclick="clearDisplay()">C</button></td>
                    </tr>
                    <tr>
                    <td><button class="square" onclick="appendToDisplay('4')">4</button></td>
                    <td><button class="square" onclick="appendToDisplay('5')">5</button></td>
                    <td><button class="square" onclick="appendToDisplay('6')">6</button></td>
                    <td><button class="white" onclick="appendToDisplay('10000')">万券</button></td>
                    </tr>
                    <tr>
                    <td><button class="square" onclick="appendToDisplay('1')">1</button></td>
                    <td><button class="square" onclick="appendToDisplay('2')">2</button></td>
                    <td><button class="square" onclick="appendToDisplay('3')">3</button></td>
                    <td><button class="yellow" onclick="calculateResult('credit')">クレ</button></td>
                    </tr>
                    <tr>
                    <td><button class="square" onclick="appendToDisplay('0')">0</button></td>
                    <td colspan="2"><button class="rectangle" onclick="appendToDisplay('00')">00</button></td>
                    <td><button class="blue" onclick="calculateResult('cash')">現計</button></td>
                    </tr>
                </table>
                </div>
            </div>
            </div>
            <!-- 左画面 -->
            <div class="split-left-64">
            <div class="split-left__inner">
                <div class="vertical-list">
                <div class="sum">
                    <span class="label">合計数</span>
                    <span class="number" id="total-count">0</span>
                    <span class="unit-point"id="slip-number">点　#{{ $slip_number }}</span>
                </div>
                <div class="product-list" id="productList">
                    <div class="item">
                    <!-- <span class="label">商品A</span>　　ここにjsで差し込んでる
                    <span class="item-number" id="item-count">1</span>
                    <a href="" class="unit-cancel css-x" id="item-cancel" onclick=""></a>-->
                    </div> 
                </div>
                <div class="subtotal">
                    <span class="label">小計</span>
                    <span class="number" id="subtotal-count">0</span>
                    <span class="unit-yen">円</span>
                </div>
                
                <label class="popup-open" for="popup" onclick="openCalc(this)">会計へ進む</label>
                
                </div>
            </div>
            </div>
            <!-- 右画面 -->
            <div class="split-right-64">
            <div class="split-right__inner">
                <div class="regi-header"></div>
                <x-hamburger />
                <div class="container">
                    @foreach ($products as $product)
                            <a href="#" class="btn-product" onclick="event.preventDefault();addItem(this)" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                <span class="btn-title">{{ $product->name }}</span>
                                <span class="btn-description">{{ $product->price }}円</span>
                            </a>
                    @endforeach
                </div>
            </div>
            </div>
        </main>
        <script src="js/regi.js"></script>
        <script src="js/redirect.js"></script>
    </body>
</html>