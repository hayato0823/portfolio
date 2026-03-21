// グローバル変数
let totalG = 0;
let jsonG;

document.addEventListener('DOMContentLoaded', () => {
  // 金種ごとの額面（上から順に対応）
  const denominations = [10000, 5000, 2000, 1000, 500, 100, 50, 10, 5, 1];

  // 各行のtrを取得（先頭行はヘッダ相当なのでtbodyのtrを素直に回す）
  const rows = document.querySelectorAll('table tbody tr');

  // 合計表示部分（右側の大きい合計数値）
  const totalValueEl = document.querySelector('.total-value');

  // 全入力
  const inputs = document.querySelectorAll('input[type="number"]');

  // 入力イベント付与
  inputs.forEach((input) => {
    input.addEventListener('input', updateAll, { passive: true });
  });
  //  フォーマット処理
  const formatJPY = (n) =>
    n.toLocaleString('ja-JP', { style: 'currency', currency: 'JPY' }); 

  function updateAll() {//更新処理
    let total = 0;
    const breakdown = [];

    rows.forEach((row, idx) => {
      const denom = denominations[idx];

      const tds = row.querySelectorAll('td');
      const numberInputs = row.querySelectorAll('input[type="number"]');

      let roll = 0; // 本（50枚巻き）
      let quantity = 0; // 枚
      let count = 0;

      if (numberInputs.length === 2) {
        // 硬貨の行（本＋枚）
        roll = parseInt(numberInputs[0].value, 10);
        quantity = parseInt(numberInputs[1].value, 10);
        roll = Number.isNaN(roll) ? 0 : roll;
        quantity = Number.isNaN(quantity) ? 0 : quantity;
        count = roll * 50 + quantity; // 1本=50枚[web:7][web:16]
      } else if (numberInputs.length === 1) {
        // 紙幣の行（枚のみ）
        quantity = parseInt(numberInputs[0].value, 10);
        quantity = Number.isNaN(quantity) ? 0 : quantity;
        count = quantity; // 枚[web:7][web:16]
      }

      const amount = denom * count;

      // 行ごとの小計表示
      tds[3].textContent = formatJPY(amount); 
      total += amount;

      breakdown.push({
        denomination: denom,
        roll,
        quantity,
        total: amount,
      });
    });

    totalValueEl.textContent = total.toLocaleString('ja-JP'); 

    // サーバ送信用データ
    const money = {
      breakdown,
      total,
    };

    const json = JSON.stringify(money);

    // グローバルへ
    totalG = total;
    jsonG = json;
  }

  // 初期表示
  updateAll();

  // リセットボタン
  const resetBtn = document.querySelector('.reset-btn');
  resetBtn?.addEventListener('click', () => {
    inputs.forEach((input) => {
      input.value = 0;
      input.setAttribute('value', '0'); 
    });

    //初期表示
    updateAll();
  });

  // OKボタン db登録
  const okBtn = document.querySelector('.confirm-btn');
  okBtn?.addEventListener('click', async () => {
    if (totalG <= 0) {
      alert('入力情報がありません');
      return;
    }
    if (!confirm('入力内容を確定してよろしいですか？（後から変更できません）')) {
      return;
    }
    try {
      const res = await fetch('/open_register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: jsonG,
      });
      const data = await res.json();
      // console.log('サーバーからの返答', data);
      if (data.success === true) {
        alert('ホーム画面に移動します');
        window.location.replace('/home');
      }
    } catch (e) {
      alert('通信に失敗しました。');
    }
  });
});
