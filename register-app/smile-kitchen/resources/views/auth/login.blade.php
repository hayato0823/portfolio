<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/豚ちゃん.png') }}" type="image/png">

    <title>SmilePOS-ログイン</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        /* 基本設定：全ての要素の幅計算を「枠線込み」にする */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            height: 100%;
            font-size: 16px; /* 基準サイズ（1rem = 16px） */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
            background-color: #ffffff;
            
            /* 画面いっぱいに広げて中身を中央寄せにする */
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* フォーム全体を囲むコンテナ */
        .container {
            width: 100%;
            max-width: 800px; /* PCなど広い画面での最大幅 */
            padding: 2rem;    /* 画面端との余白 */
        }

        /* フォームの設定 */
        form {
            width: 100%;
            /* 必要であればここに背景色や影を追加してカード化できます */
            /* background-color: #fff; */
            /* padding: 2rem; */
            /* border-radius: 1rem; */
            /* box-shadow: 0 4px 15px rgba(0,0,0,0.1); */
        }

        .form-group {
            margin-bottom: 3rem; /* 48px相当 */
        }

        .form-label {
            display: block;
            text-align: center;
            margin-bottom: 1rem;
            font-size: 2rem; /* 32px相当 */
            color: #4a5568;
            font-weight: bold;
        }

        .form-input {
            width: 100%;
            padding: 1.2rem;
            padding-right: 4rem; /* アイコンが被らないように右に余白 */
            
            border: 3px solid #3b82f6;
            border-radius: 12px;
            font-size: 2rem; /* 32px相当：入力文字を大きく */
            text-align: center;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: #2563eb; /* フォーカス時に色を少し濃く */
        }

        .form-button {
            width: 100%;
            padding: 1.5rem 0;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 2.2rem; /* ボタン文字は大きく */
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
            
            /* スマホ・タブレットでのタップ時のハイライトを無効化 */
            -webkit-tap-highlight-color: transparent;
        }

        .form-button:active {
            opacity: 0.8;
        }

        .error-message ul {
            list-style: none;
            padding: 0;
            margin: 1rem 0 0 0;
            color: #e53e3e;
            text-align: center;
            font-size: 1.5rem;
        }

        /* パスワード入力欄のラッパー */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        /* パスワード表示切替ボタン */
        .toggle-btn {
            position: absolute;
            right: 1.5rem; /* 右端からの距離 */
            
            background: none;
            border: none;
            font-size: 2rem; /* アイコンサイズ */
            cursor: pointer;
            padding: 0;
            
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a5568;
        }

        /* --- レスポンシブ調整 --- */
        
        /* 小さなタブレットやスマホ向け (768px以下) */
        @media screen and (max-width: 768px) {
            html {
                font-size: 14px; /* 全体の基準サイズを少し小さくする */
            }
            
            .container {
                padding: 1.5rem;
            }

            .form-input, .form-button {
                border-radius: 8px; /* 角丸を少し控えめに */
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="container">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600" style="text-align: center; font-size: 1.5rem;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="form-group">
                <label for="email" class="form-label">
                    ID
                </label>
                <input id="email"
                       class="form-input"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username">
                
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <div class="password-wrapper">
                    <input id="password"
                           class="form-input"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password">
                    <button type="button" class="toggle-btn" id="toggleBtn">●</button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>


            <div>
                <button type="submit" class="form-button">
                    ログイン
                </button>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const toggleBtn = document.getElementById('toggleBtn');
                
                toggleBtn.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleBtn.textContent = '○'; // 白丸など、わかりやすい記号に変更可
                    } else {
                        passwordInput.type = 'password';
                        toggleBtn.textContent = '●';
                    }
                });
            });
            </script>
        </form>
    </div>
</body>
</html>