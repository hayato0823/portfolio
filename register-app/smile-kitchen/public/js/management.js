function today() {//初期は当日にする
    var getToday = new Date();
    var y = getToday.getFullYear();
    var m = getToday.getMonth() + 1;
    var d = getToday.getDate();
    var currentDate = y + "-" + m.toString().padStart(2, '0') + "-" + d.toString().padStart(2, '0');
    document.getElementById("datepicker").value = currentDate;
    sendDate(currentDate); // 初期は当日の日付をサーバーに送る
}

function dayget() {//変更された日付を取得
    const currentDate = document.getElementById("datepicker").value;
    sendDate(currentDate); // 変更された日付をサーバーに送る
}

function sendDate(el) {//サーバー通信
    var json = JSON.stringify(el);
    fetch('/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: json
    })
    .then(response => {//サーバーから受け取る
        console.log('Response Status:', response.status);
        return response.json();//json文字列をjsオブジェクトに変換する
    })
    .then(data => {
        if(data.success === false) {
            alert(data.message);
            //データがない時は画面をクリアにする
            document.getElementById('allSale').textContent = '￥0';//総売上
            document.getElementById('allCash').textContent = '￥0';//現計合計
            document.getElementById('allCredit').textContent = '￥0';//クレジット合計
            document.getElementById('allTransaction').textContent = 0;//総取引数
            const tableBody = document.getElementById('itemTable'); // アイテムテーブルをクリア
            tableBody.innerHTML = '';
            itemChart.destroy();//グラフを消す
        } else if (data.success === true) {
            render(data);//描画のためにデータを渡す
        }
    })

}

function render(el) {//小分類・大分類描画  
    //大分類
    document.getElementById('allSale').textContent = '￥' + String(el.allSale);//総売上
    document.getElementById('allCash').textContent = '￥' + String(el.allCash);//現計合計
    document.getElementById('allCredit').textContent = '￥' + String(el.allCredit);//クレジット合計
    document.getElementById('allTransaction').textContent = String(el.allTransaction);//総取引数

    //小分類
    const n = el.items.length;//商品数
    const names = el.items.map(x => x.item_name);//商品名配列
    const prices = el.items.map(x =>  '￥' + Number(x.price).toLocaleString('ja-JP'));//価格
    const quantitys = el.items.map(x => (x.total_quantity));//販売個数
    const item_sales = el.items.map(x =>  '￥' + Number(x.total_sales).toLocaleString('ja-JP'));//価格

    const tableBody = document.getElementById('itemTable'); // アイテムテーブルをクリア
    tableBody.innerHTML = '';  

    for(let i = 0; i < n; i++) {
        const tableBody = document.getElementById('itemTable');//テーブル情報の取得
        const newRow = document.createElement('tr');//行要素の作成
        
        newRow.innerHTML = `
            <td>${names[i]}</td>
            <td>${prices[i]}</td>
            <td>${quantitys[i]}</td>
            <td>${item_sales[i]}</td>
        `;
        tableBody.appendChild(newRow);//一番下に挿入
    }
    makeChart(names,quantitys);
}
let itemChart = null;   // グラフを保持するグローバル変数

function makeChart(names,quantitys){
    if (itemChart !== null) {//古いグラフがあったら消す
        itemChart.destroy();
    }
    console.log("商品名",names)
    console.log("販売個数",quantitys)

    const ctx = document.getElementById('itemChart');

    itemChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: names,
            datasets: [{
                label: '販売個数',
                data: quantitys,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,   // 親(#chartArea)にフィット
            plugins: {
                legend: {
                position: 'top',          
                align: 'center',
                },
                labels: {
                    boxWidth: 16,
                    color: '#333',
                    font: { size: 40 }
                }
            }
        }
    });
}

today();