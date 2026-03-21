// グローバル変数
let totalG = 0;
let differenceG = 0;
let jsonG;

document.addEventListener('DOMContentLoaded', () => {
  // 金種ごとの額面（上から順）
  const denominations = [10000, 5000, 2000, 1000, 500, 100, 50, 10, 5, 1];

  // tbody 行を素直に取得
  const rows = document.querySelectorAll('table tbody tr');

  // 実ドロア在高の数値表示要素（右ペイン1段目の右側2つ目のspan）
  const actualTotalEl = document.querySelector('.result-box .result-title:nth-of-type(1) .result-number span:nth-of-type(2)');

  // 理論現金在高（サーバ埋め込み）を取得
  const expectedCashEl = document.querySelector('.result-box .result-title:nth-of-type(2) .result-number span:nth-of-type(2)');
  const expectedCashStr = (expectedCashEl?.textContent || '0').replace(/[^\d.-]/g, '');
  const expectedCash = Number(expectedCashStr) || 0;
  expectedCashEl.textContent = expectedCash.toLocaleString('ja-JP'); // 例: "1,234,567"[web:21]


  // 差異の表示要素
  const differenceEl = document.querySelector('.result-box .result-title:nth-of-type(3) .difference-number span:nth-of-type(2)');

  // 入力欄
  const inputs = document.querySelectorAll('input[type="number"]');

  // フォーマット
  const formatJPY = (n) => n.toLocaleString('ja-JP', { style: 'currency', currency: 'JPY' }); 

  // 入力イベント
  inputs.forEach((input) => {
    input.addEventListener('input', updateAll, { passive: true });
  });

  function updateAll() {
    let total = 0;
    const breakdown = [];

    rows.forEach((row, idx) => {
      const denom = denominations[idx];
      const tds = row.querySelectorAll('td');
      const numberInputs = row.querySelectorAll('input[type="number"]');

      let roll = 0;      // 本（50枚巻き）
      let quantity = 0;  // 枚
      let count = 0;

      if (numberInputs.length === 2) {
        // 硬貨行（本＋枚）
        roll = parseInt(numberInputs[0].value, 10);
        quantity = parseInt(numberInputs[1].value, 10);
        roll = Number.isNaN(roll) ? 0 : roll;           
        quantity = Number.isNaN(quantity) ? 0 : quantity;
        count = roll * 50 + quantity;                   // 1本=50枚
      } else if (numberInputs.length === 1) {
        // 紙幣行（枚のみ）
        quantity = parseInt(numberInputs[0].value, 10);
        quantity = Number.isNaN(quantity) ? 0 : quantity;
        count = quantity;
      }

      const amount = denom * count;

      // 4列目の小計
      tds[3].textContent = formatJPY(amount);        

      total += amount;

      breakdown.push({ denomination: denom, roll, quantity, total: amount });
    });

    // 実ドロア在高の数値
    actualTotalEl.textContent = total.toLocaleString('ja-JP'); 

    // 差異計算と表示
    const difference = total - expectedCash;
    differenceEl.textContent = difference.toLocaleString('ja-JP'); 

    // グローバル
    totalG = total;
    differenceG = difference;

    // 送信用
    const money = { breakdown, total, difference, expected_cash: expectedCash };
    jsonG = JSON.stringify(money);
  }

  // 初期表示
  updateAll();

  // リセットボタン（HTML: .reset-btn）
  const resetBtn = document.querySelector('.reset-btn');
  resetBtn?.addEventListener('click', () => {
    inputs.forEach((input) => {
      input.value = 0;
      input.setAttribute('value', '0');
    });
    updateAll();
  });

  // OKボタン
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
      const res = await fetch('/close_register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: jsonG,
      });
      const data = await res.json();
      if (data.success === true) {
        alert('ホーム画面に移動します');
        window.location.replace('/home');
      }
    } catch (e) {
      alert('通信に失敗しました。');
    }
  });
});
