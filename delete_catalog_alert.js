$(document).on('click', '.delete_catalog_alert', function() {
  var val = $(this).val();
  var name =  $(this).attr("name");
  console.log(name);
  swal({
    title: "カタログを削除します",
    text: "削除したカタログは復元できません。登録済みのアイテムも同時に削除されます。削除してよろしいですか？",
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
        url: "//catamake.com/delete_catalog.php",
        data: {"catalog_id": val}
      })
      .then(
        function (data) {
          // 1つめは通信成功時のコールバック
          console.log(data);
          if(name=="catalog_page"){
            window.location.href = "/";
            // swal("カタログを削除しました", "", "success");
          }else{
            var id = '#catalog' + val;
            $(id).hide();
            swal("カタログを削除しました", "", "success");
          }
        },
          // 2つめは通信失敗時のコールバック
          function (data) {
            console.log(data);
            alert("読み込み失敗");
          }
      );

    }else{
      // 何もしない
    }
  });
});
