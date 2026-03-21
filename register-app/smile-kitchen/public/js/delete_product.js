function deleteItem(el) {
    if (!confirm("本当に削除しますか？（後から変更できません）")) {
        return;
    }
    const id = (el.id);//削除する商品のidを取得
    // サーバーに送る
        const itemId = JSON.stringify({ id });
        console.log(id);

        fetch('/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: itemId
        })
        .then(response => {
            console.log('Response Status', response.status);
            return response.json();
        })
        .then(data => {
            // 削除
            console.log('json:', data);
            if (data.success) {
                el.closest('tr').remove();
            } else {
                alert('削除に失敗しました');
            }
        });

}
