function makeAlert() {
    const alertElement = document.querySelector(".aleart");
    if (alertElement) {
        const messageId = alertElement.id;
        console.log("レジ金登録:", messageId);
        if (messageId === 'before') {
            alert("開店前レジ金が登録されていません");
        } else if (messageId === 'already') {
            alert("開店前レジ金は登録済みです"); 
        } else if(messageId === 'finish') {
            alert("レジ締めは終了しています");
        } else if (messageId === 'nothing') {
            alert("売り上げが存在しません");
        }
    }
}

makeAlert();
