let backupRow = null;//バックアップをグローバルに保存
function editItem(el) {
  var step = document.querySelectorAll('.edit').length;//編集中の行の存在確認

  if (step === 0) {//編集中の行がなければ流す
      const id = (el.id);//編集する商品のidを取得
      const editRow = event.target.closest('tr');//書き換え対象行
      const inputRow = document.createElement('tr');//入力行
      inputRow.setAttribute('data-id', id);//入力行にid付与
      backupRow = editRow.cloneNode(true);//キャンセル時のために保存

      inputRow.innerHTML = `
        <td>${id}</td>
        <td><input type="text" id="edit_name" class="edit" placeholder="商品名"></td>
        <td><input type="number" id="edit_price" class="edit" placeholder="価格"></td>
        <td>
          <input type="hidden" id="edit_id" value="${id}">
          <button type="button" class="update-btn" id="update_btn">更新</button>
          <button type="button" class="cancel-btn" id="cancel_btn">キャンセル</button>
        </td>
      `;
      editRow.parentNode.replaceChild(inputRow, editRow);
    } else {
      alert("すでに編集中の行があります");
    }
    return; // 他の処理に流れないように

}







///////////////////////////////////////////////////////////////////////////////////////////////////////////
//let backupRow = null;//バックアップをグローバルに保存
// document.addEventListener('click', function(event) {
//   // 編集ボタンが押されたとき
//   if (event.target.classList.contains('edit-btn')) {
//     var step = document.querySelectorAll('.edit').length;//編集中の行の存在確認
//     if (step === 0) {//編集中の行がなければ流す
//       const id = event.target.id;
//       const editRow = event.target.closest('tr');//書き換え対象行
//       const inputRow = document.createElement('tr');//入力行
//       inputRow.setAttribute('data-id', id);//入力行にid付与
//       backupRow = editRow.cloneNode(true);//キャンセル時のために保存

//       inputRow.innerHTML = `
//         <td>${id}</td>
//         <td><input type="text" id="edit_name" class="edit" placeholder="商品名"></td>
//         <td><input type="number" id="edit_price" class="edit" placeholder="価格"></td>
//         <td>
//           <input type="hidden" id="edit_id" value="${id}">
//           <button type="button" class="update-btn" id="update_btn">更新</button>
//           <button type="button" class="cancel-btn" id="cancel_btn">キャンセル</button>
//         </td>
//       `;
//       editRow.parentNode.replaceChild(inputRow, editRow);
//     } else {
//       alert("すでに編集中の行があります");
//     }
//     return; // 他の処理に流れないように
//   }

//   // 更新ボタンが押されたとき
//   if (event.target.classList.contains('update-btn')) {
//     const productId = document.getElementById('edit_id').value;
//     const productName = document.getElementById('edit_name').value;
//     const productPrice = document.getElementById('edit_price').value;

//     console.log('商品ID', productId);
//     console.log('更新商品名', productName);
//     console.log('更新価格', productPrice);

//     var editItem = {//配列化
//       id: productId,
//       name: productName,
//       price: parseInt(productPrice),
//     };

//     var json = JSON.stringify(editItem);//json化

//     fetch('/edit', {//サーバーに送る
//       method: 'post',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//       },
//       body: json
//     })
//     .then(response => {
//       console.log('Response Status:', response.status);
//       return response.json();
//     })
//     .then(data => {//サーバーから受け取る
//       //重複回避機能
//       if (data.success === false) {
//         document.querySelectorAll('.edit').forEach(input => input.value = '');//フォームを空にする
//         alert(data.error);
//       } else {
//         // 更新内容を元に書き換え
//         const inputRow = event.target.closest('tr');
//         const newRow = document.createElement('tr');
//         newRow.setAttribute('data-id', productId);
//         newRow.innerHTML = `
//           <td>${productId}</td>
//           <td>${data.received_name}</td>
//           <td>${data.received_price}</td>
//           <td>
//             <button type="button" class="delete-btn" id="${productId}">削除</button>
//             <button type="button" class="edit-btn" id="${productId}">編集</button>
//           </td>
//         `;
//         inputRow.parentNode.replaceChild(newRow, inputRow);
//       }
//       console.log('json', data);
//     });
//     return;
//   }
//   // キャンセルボタンが押されたとき
//   if (event.target.classList.contains('cancel-btn')) {
//     const inputRow = event.target.closest('tr');
//     if (backupRow) {
//       inputRow.parentNode.replaceChild(backupRow, inputRow);
//       console.log('キャンセルされました')
//       backupRow = null;//バックアップを空にする
//     }
//   }
// });
