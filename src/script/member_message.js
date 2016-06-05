window.onload = function () {
    var all_chkbt = document.getElementById('all');
    var form = document.getElementsByTagName('form')[0];
    all_chkbt.onclick = function () {
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].name != 'chkall') {
                form.elements[i].checked = form.chkall.checked;
            }
        }
    };
    form.onsubmit = function () {
      if (confirm("你确定进行此操作吗?")) {
          return true;
      }
      return false;
    };
};