function addItem() {
    // 入力内容を取得
    const productName = document.getElementById('product-name').value;
    const productPrice = document.getElementById('product-price').value;
    // バリデーションチェック
    if(productPrice <= 0) {
        alert('値段は1以上を入力してください');
        return;
    }
    if(productName === "" || productPrice === "") {
        alert('商品名と価格を入力してください');
        return;
    }
    if(productPrice.length > 5) {
        alert('価格は99,999以内で入力してください');
        return;
    }
    //入力内容を配列化
    var item ={
        name:productName,
        price:parseInt(productPrice),//数値化
    }
    //json化
    var json = JSON.stringify(item);

    console.log("JSON",json);//入力内容

    //サーバーに送る   
    fetch('/enter', {
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
        console.log('json:', data);  //サーバーからのデータを確認
    
        if(data.success === false){//重複回避機能
            alert(data.error); 
            document.getElementById('product-form').reset();//フォームを空にする
        } else {
            document.getElementById('product-form').reset();//フォームを空にする
            //ここから行の追加
            const tableBody = document.querySelector('tbody');//テーブル情報を取得
            const newRow = document.createElement('tr');//行要素の作成
            newRow.setAttribute('data-id', data.itemId);

            let formattedPrice = data.received_price;
            formattedPrice = formattedPrice.toLocaleString('ja-JP');//値段にカンマを入れる

            newRow.innerHTML = `
                <td>${data.received_name}</td>
                <td>${formattedPrice}</td>
                <td>
                    <button type="button" class="edit" id="${data.itemId}" onclick="deleteItem(this)">削除</button>
                </td>
            `;
            tableBody.appendChild(newRow);//一番下に挿入
            //挿入した行を５秒間ハイライト表示
            newRow.classList.add('highlight');
            setTimeout(() => {
                newRow.classList.remove('highlight');
            }, 3000);

            tableBody.lastElementChild.scrollIntoView({//追加したのが分かるようにスクロール
                behavior: 'smooth',
                block: 'end'
            });
        }

    })

}

