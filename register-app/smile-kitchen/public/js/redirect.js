function redirect(el) {
    const redirectMap = {// value:/blade名
        "home": "/home",                     //ホーム画面
        "regi": "/regi",                    // レジ画面
        "add_product": "/add_product",     // 商品登録画面
        "open_close": "/open_close",      // レジ開け締め選択画面
        "open_register": "/open_register",// レジ開け画面
        "close_register": "/close_register",// レジ締め画面
        "history_management":"/history_management",//データ管理選択画面
        "history":"/history",               //販売履歴画面
        "management": "/management",     // 売上管理画面 
    };
    const value = el.getAttribute('value')//遷移先を取得

    if (redirectMap[value]) {
                window.location.href = redirectMap[value];
            } else {
                alert("制作中です");
            }

}
function notclick() {
    alert("展示中のため、選択できません");
}

