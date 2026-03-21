<!DOCTYPE html>
<html lang="ja">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/split.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">
    <title>SmilePOS-商品登録</title>
</head>

<body class="split-page">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <header>
        商品登録
    </header>
    <x-hamburger />
    <main class="split-55">
        <!-- 左画面 -->
        <div class="split-left-55">
            <div class="split-left__inner">
                <div class="input-container">
                    <form id="product-form"><!--このformタグは自分で追加分 -->
                        <div class="input-product-name">
                            <h2>商品名</h2>
                            <input type="text" id="product-name" name="product-name" maxlength="15" placeholder="例：りんご（１５字以内）" required>
                            <div class="error-message">＊商品名を入力してください</div>
                        </div>
                        <div class="input-price">
                            <h2>値段</h2>
                            <input type="number" id="product-price" name="price" max="99999" placeholder="例：150（1~99,999以内）" required>
                            <div class="error-message">＊値段を入力してください</div>
                        </div>
                        <button type="button" class="enter" onclick="addItem()">登録</button>
                    </form>
                </div>
            </div>

        </div>


        <!-- 右画面 -->
        <div class="split-right-55">
            <div class="split-right__inner">
                <div class="scroll-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>商品名</th>
                                <th>値段</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr data-id="{{ $product->id }}">
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($product->price) }}</td>
                                    <td>
                                        @csrf
                                        <button type="button" class="edit" id="{{ $product->id }}" onclick="deleteItem(this)">削除</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>    
            </div>

        </div>


    </main>
    <script src="js/add_product.js"></script>
    <script src="js/delete_product.js"></script>
    <script src="js/redirect.js"></script>

    
</body>
</html>