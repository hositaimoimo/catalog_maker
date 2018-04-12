$(document).on('click', '.lock', function() {
  var vals = $(this).val().split(',');  // lock:0, unlock:1
  console.log(vals);
  $.ajax({
    type: "POST",
    url: "//catamake.com/catalog_lock.php",
    data: {
      "catalog_id": vals[0],
      "catalog_lock": vals[1]
    }
  })
  .then(
    function (data) {
      // 1つめは通信成功時のコールバック
      var icon_id = "lock_icon" + vals[0];
      var btn_id = "lock_btn" + vals[0];
      console.log(icon_id);
      if(vals[1] == 0){
        // ロックを解除
        document.getElementById(icon_id).className="fa fa-unlock-alt";
        document.getElementById(btn_id).value = vals[0] + "," + 1;
      }else{
        // ロック
        document.getElementById(icon_id).className="fa fa-lock";
        document.getElementById(btn_id).value = vals[0] + "," + 0;
      }
    },
      // 2つめは通信失敗時のコールバック
      function (data) {
        console.log(data);
        alert("読み込み失敗");
      }
  );
});
