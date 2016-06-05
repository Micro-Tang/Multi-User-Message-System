window.onload = function () {
    var fm = document.getElementsByTagName('form')[0];
    var pass = document.getElementById('pass');
    fm[1].onclick = function () {
        pass.style.display = 'none';
    };

    fm[2].onclick = function () {
        pass.style.display = 'block';
    };

    fm.onsubmit = function () {
        if (fm.name.value.length < 2 || fm.name.value.length > 20) {
            alert('相册名不得小于2位或者大于20位');
            return false;
        }

        if (fm[2].checked) {
            if (fm.password.value.length < 6) {
                alert('密码不得小于6位');
                return false;
            }
        }

        if (fm.content.value.length > 200) {
            alert('相册简介不得大于200位');
            return false;
        }
        return true;
    };
};