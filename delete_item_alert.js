$(document).on('click', '.delete_item_alert', function() {
  var val = $(this).val();
  swal({
    title: "アイテムを削除します",
    text: "削除したアイテムは復元できません。削除してよろしいですか？",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: '削除する',
    cancelButtonText: "キャンセル",
    closeOnConfirm: false,
  },
  function(isComfirm){
    if(isComfirm){
      $.ajax({
        type: "POST",
        url: "//catamake.com/delete_item.php",
        data: {"item_id": val}
      })
      .then(
        function (data) {
          // 1つめは通信成功時のコールバック
          console.log(data);
          var id = '#item' + val;
          $(id).hide();
        },
          // 2つめは通信失敗時のコールバック
          function (data) {
            console.log(data);
            alert("読み込み失敗");
          }
      );
      swal("アイテムを削除しました", "", "success");
    }else{
      // 何もしない
    }
  });
});
