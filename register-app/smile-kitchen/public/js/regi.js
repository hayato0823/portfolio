const names = [];//カート内の商品名
const onePrices = [];//一個当たりの値段
const prices = [];//商品別合計金額
const qty = [];//商品別個数
let slip_number = 1;//伝票番号の初期値
let subtotal = 0;//電卓小計
let receipts = [];

function addItem(el){//商品を配列に追加
    const name = (el.dataset.name);
    const price = parseInt(el.dataset.price, 10);

    if(!names.includes(name)) {//カートに入っていない商品の場合
        names.unshift(name);//先頭に追加
        onePrices.unshift(price);//先頭に追加
        prices.unshift(price);//先頭に追加
        qty.unshift(1);//先頭に追加
    } else {//カートに入っている商品の場合
        const here = names.indexOf(name);//配列内の位置を特定
        prices[here] = (prices[here] ?? 0) + price;//金額を加算
        qty[here] = (qty[here] ?? 0) + 1;//個数を加算

        //商品を追加したら先頭に移動させる
        // まとめて退避
        const movedName = names.splice(here, 1)[0];
        const movedPrice = prices.splice(here, 1)[0];
        const movedQty = qty.splice(here, 1)[0];
        const movedOnePrice = onePrices.splice(here, 1)[0];

        // 先頭に追加
        names.unshift(movedName);
        prices.unshift(movedPrice);
        qty.unshift(movedQty);
        onePrices.unshift(movedOnePrice);

        console.log(names, prices, qty, onePrices);
    }
    
    sumBy();//計算用関数の呼び出し
    clearDisplay();//商品追加時に電卓をリセット
}

function sumBy() {//配列を元に計算
    const totalQty =  qty.reduce((acc, v) => acc + v, 0);//注文数合計
    const totalPrice =  prices.reduce((acc, v) => acc + v, 0);//金額合計
    subtotal = totalPrice;
    const formattedPrice = totalPrice.toLocaleString('ja-JP');//合計にカンマを入れる
    subtotal = formattedPrice;

    document.getElementById('total-count').textContent = String(totalQty);//注文数合計を画面に反映
    document.getElementById('subtotal-count').textContent = String(formattedPrice);//合計金額を画面に反映

    makeReceipt();//レシート作成関数の呼び出し
}
function makeReceipt() {//伝票の作成＆画面表示
    receipts = []; //伝票配列の用意＆初期化
    productList.textContent = '';
    for (let i = 0; i < names.length; i++) {
        const row = [slip_number, names[i],onePrices[i],qty[i],prices[i],];
        receipts.push(row);

        const productList = document.getElementById('productList');
        const newRow = document.createElement('div');
        newRow.setAttribute('class','item');
        

        newRow.innerHTML = `
            <span class="label">${names[i]}</span>
            <span class="item-number" id="item-count">${qty[i]}</span>
            <a href="#" class="unit-cancel css-x" id="item-cancel" onclick="event.preventDefault();deleteItem(this)" data-here="${(i)}"></a>
        `;
        productList.appendChild(newRow);
    }
}

function deleteItem(el) {//カート内商品削除
    const here = (el.dataset.here);//削除する商品の配列内の位置
    names.splice(here,1);
    prices.splice(here,1);
    qty.splice(here,1);
    onePrices.splice(here, 1);

    sumBy();
    change();
}

function openCalc() {//電卓開いたとき
    document.getElementById('subtotal-value').textContent = String(subtotal) + '円';//小計を電卓画面に反映
}

let money = 0;//預り金の初期値

function appendToDisplay(n) {

    if (n === "00") {
        if (money >= 10000) {
        // 万券が押されている場合：下4桁にだけ00を追加（2桁右シフト）
        const manPart = Math.floor(money / 10000);   // 万以上
        const lowerPart = money % 10000;             // 下4桁
        const newLower = lowerPart * 100;            // 下4桁に00追加
        const carryOver = Math.floor(newLower / 10000); // 100の付与で繰り上がった分
        money = (manPart + carryOver) * 10000 + (newLower % 10000);
        } else {
        // 通常時は100倍（末尾に00を付ける）
        money = money * 100;
        }
    } else if (n === "10000") {
        money = money + 10000;
    } else {
        // 0~9の数字入力
        const digit = Number(n);

        if (money >= 10000) {
        // 万の位は保持し、下4桁に新しい数字を追加（繰り上がり対応）
        const manPart = Math.floor(money / 10000);
        const lowerPart = money % 10000;
        const newLower = lowerPart * 10 + digit;
        const carryOver = Math.floor(newLower / 10000);
        money = (manPart + carryOver) * 10000 + (newLower % 10000);
        } else {
        money = money * 10 + digit;
        }
    }

    const formattedMoney = money.toLocaleString('ja-JP'); // 表示はロケール指定推奨
    document.getElementById('paid-value').textContent = String(formattedMoney) + '円';

    change();
}
function clearDisplay() {//Cボタン機能
    money = 0;
    document.getElementById('paid-value').textContent = String(money)+'円';//預り金を画面に反映

    change();
}

function change() {
    const simpleSubtotal =
        (typeof subtotal === "string")
        ? Number(subtotal.replace(/,/g, ""))
        : Number(subtotal || 0);

    const change = money - simpleSubtotal;

    const changeElement = document.getElementById("change-value");
    changeElement.dataset.change = String(change);
    changeElement.textContent = change.toLocaleString("ja-JP") + "円";
}


function calculateResult(type) {//現計orクレジットボタン機能
    if(subtotal === 0) {
        alert('商品が選択されていません');
        return;
    }
    const el = document.getElementById('change-value'); 
    const raw = el.dataset.change ?? '';               
    let isOverZero = Number.parseInt(raw, 10) || 0; 
    const getMoney = document.getElementById('paid-value').textContent;
    if(type === 'credit' || getMoney === '0円') {
        if(!confirm('会計を確定してよろしいですか？')){     //クレジットor預り金0円の場合の確認ダイアログ 会計効率が悪かったらこのif文ごと削除
            return;
        }
        isOverZero = 0;//クレジットor預り金0円の場合はお釣りを0にする
    }
    if(isOverZero >= 0) {

        for(let i=0; i < receipts.length; i++) {
            receipts[i].push(type);
        };
        const json = JSON.stringify(receipts);//伝票のjson化
        console.log("サーバー送信内容",json);
        //サーバーに送る   
            fetch('/regi', {
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
            .then(data => {//promiseチェーンの仕組みで勝手に次の引数（data）にデータが入る
                console.log('サーバー返答', data);  //サーバーからのデータを確認
                if (data.success === true) {
                    setTimeout(() => {
                        const checkbox = document.getElementById('popup');// 会計が済んだ1秒後にポップアップを閉じる＆初期化
                        if (checkbox) {
                            checkbox.checked = false;
                            names.length = 0;
                            onePrices.length = 0;
                            prices.length = 0; 
                            qty.length = 0;
                            subtotal.length = 0;
                            receipts.length = 0;

                            slip_number = slip_number+1;//次の番号へ
                            document.getElementById('slip-number').textContent = "点　#"  + String(slip_number);

                            sumBy();
                            clearDisplay();
                            change();
                        }
                    }, 1000);
                }
            })

        } else {//お釣りがマイナスの場合
            alert('金額が不足しています');
    }    
}

function getslip() {
    let slipText = document.getElementById('slip-number').textContent;

    // 数字だけを抽出
    let match = slipText.match(/\d+/);
    if (match) {
        slip_number = Number(match[0]);
    } else {
        console.error("伝票番号が取得できませんでした");
    }
}


getslip();//最初に伝票番号を取得