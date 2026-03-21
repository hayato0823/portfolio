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
    })