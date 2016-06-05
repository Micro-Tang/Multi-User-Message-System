window.onload = function () {
    var can = document.getElementById("cancle");
    var del = document.getElementById("delete");
    can.onclick = function() {
      location.href="member_message.php";
    };
    del.onclick = function() {
        if (confirm("是否删除")) {
            location.href="?action=delete&tg_id=" + this.name;
        }
    };
};