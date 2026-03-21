var globalday;//削除機能用グローバル変数
// 日付の初期値を今日にする
function today() {
    var getToday = new Date();
    var y = getToday.getFullYear();
    var m = getToday.getMonth() + 1;
    var d = getToday.getDate();
    var currentDate = y + "-" + m.toString().padStart(2, '0') + "-" + d.toString().padStart(2, '0');
    document.getElementById("datepicker").value = currentDate;
    globalday = currentDate;//グローバル変数に代入
    sendDate(currentDate); // 初期は当日の日付をサーバーに送る
}
// 日付を取得
function dayget() {
    selectedDate = document.getElementById("datepicker").value;
    globalday = selectedDate;//グローバル変数に代入
    sendDate(selectedDate); // サーバーに送る
}

// サーバー通信＆画面の書き換え
function sendDate(currentDate) {
    var json = JSON.stringify(currentDate); // json化

    fetch('/send_date', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: json
    })
    .then(response => {
        return response.json(); // json文字列をjsオブジェクトに変換する
    })
    .then(data => {
        const tableBody = document.querySelector('tbody');
        tableBody.innerHTML = ''; // 一度テーブルをクリア

        if (data.success === false) {
            setTimeout(() => {
                alert(data.message); // 画面クリア後にアラート
            }, 1);
        } else {
            const history = data.history;
            const grouped = {};//伝票番号ごとにグループ化

            for (let i = 0; i < history.length; i++) {
                const entry = history[i];//１件ずつ格納する
                const slip = entry.slip_number;//伝票番号を取り出す

                if (!grouped[slip]) { //未格納なら格納する
                    grouped[slip] = { //初期化
                        slip_number: slip,
                        items: {}, // 商品名と数量
                        totalQuantity: 0,
                        total: 0
                    };
                }

                if (!grouped[slip].items[entry.item_name]) { // 商品名ごとで集計
                    grouped[slip].items[entry.item_name] = 0;
                }
                grouped[slip].items[entry.item_name] += entry.quantity;
                grouped[slip].totalQuantity += entry.quantity;
                grouped[slip].total += entry.total;
            }

            // テーブルに出力
            for (const slip in grouped) {
                const data = grouped[slip];
                const newRow = document.createElement('tr');

                // 商品名 × 数量のリストを生成
                const itemDetails = Object.entries(data.items)//使える形に変換
                    .map(([name, qty]) => `${name} ×${qty}`) //mapは配列の中身一つ一つに同じ処理をする便利なやつ
                    .join('<br>'); //joinは配列の要素を繋げて一つの文字列にする

                    let formattedPrice = data.total;
                    formattedPrice ='￥' + formattedPrice.toLocaleString('ja-JP');//合計にカンマを入れる

                newRow.innerHTML = `
                    <td>${data.slip_number}</td>
                    <td>${itemDetails}</td>
                    <td>${data.totalQuantity}</td>
                    <td>${formattedPrice}</td>
                    <td><button type="button" class="delete-btn" id="${data.slip_number}" onclick="remove(this)">削除</button></td>
                `;

                tableBody.appendChild(newRow);
            }
        }
    });
}

// 削除ボタン（削除行idをサーバーに送る＆画面から消す）当日以外は消せないようにする
function remove(button) {
    if (!confirm("本当に削除しますか？（後から変更できません）")) {
        return;
    }

    console.log('日付',globalday);
    const slipId = button.id;
    var id_day = {
        id:slipId,
        day:globalday
    };
    var json = JSON.stringify(id_day);

    fetch('/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: json  
    })
    //サーバーから受け取る
    .then(response => {
        console.log('Response Status:', response.status);
        return response.json();//json文字列をjsオブジェクトに変換する
    })
    .then(data => {
        console.log('remove', data);  //サーバーからのデータを確認

        if (data.success === true) {
            const row = button.closest('tr');
            if (row) row.remove();
        } else if (data.success === false) {
            alert("既にレジ締が終了しているため削除できません");
            console.log(data.message);
        }
    })
    .catch(error => {
        console.log('通信エラー',error);
    })
}
// ページ読み込み時に実行
window.onload = function () {
    today();
    document.getElementById("datepicker").addEventListener("change", dayget);
}
//伝票検索
function search(idValue) {
    const searchSlip = (idValue || '').trim();
    if (!searchSlip) return;

    // 削除ボタンの id 属性で一致するものを探す
    const btn = document.querySelector(`.delete-btn[id="${CSS.escape(searchSlip)}"]`);
    if (btn) {
        const row = btn.closest('tr');
        if (row) {
            row.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}