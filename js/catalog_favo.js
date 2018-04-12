$(document).on('click', '.favo', function() {
  var vals = $(this).val().split(',');
  console.log(vals);
  $.ajax({
    type: "POST",
    url: "//catamake.com/catalog_favo.php",
    data: {
      "catalog_id": vals[0],
      "my_user_id": vals[1],
      "is_favo": vals[2] // unfavo:0, favo:1
    }
  })
  .then(
    function (data) {
      // 1つめは通信成功時のコールバック
      var icon_id = "favo_icon" + vals[0];
      var btn_id = "favo_btn" + vals[0];
      if(vals[2] == 0){
        // ファボを付与
        document.getElementById(btn_id).className="favo btn btn-warning";
        document.getElementById(btn_id).value = vals[0] + "," + vals[1] + "," + 1;
      }else{
        // ファボを解除
        document.getElementById(btn_id).className="favo btn btn-default";
        document.getElementById(btn_id).value = vals[0] + ","  + vals[1] + "," + 0;
      }
    },
      // 2つめは通信失敗時のコールバック
      function (data) {
        console.log(data);
        alert("読み込み失敗");
      }
  );
});
