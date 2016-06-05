/**
 * validate_login_form_date.js
 * Created by tangkun on 16/5/16.
 */

/**
 * validate_username {检查用户名是否为空}
 * @access public
 * @returns {boolean}
 */
function validate_username() {
    var username = document.getElementById('username').value.trim();
    if (username=="") {
        document.getElementById('ms_username').innerHTML='<span>(用户名不能为空)<img src="images/wrong.png"></span>';
        return false;
    } else {
        if (username.length < 2) {
            document.getElementById('ms_username').innerHTML='<span>(用户名长度至少两位)<img src="images/wrong.png"></span>';
            return false;
        } else if (username.length > 20) {
            document.getElementById('ms_username').innerHTML='<span>(用户名长度不能多与20位)<img src="images/wrong.png"></span>';
            return false;
        } else {
            document.getElementById('ms_username').innerHTML='<span>(成功)<img src="images/success.png"></span>';
            return true;
        }
    }
}

/**
 * validate_password() {检查密码是否为空}
 * @access public
 * @returns {boolean}
 */
function validate_password() {
    if (document.getElementById('password').value=="") {
        document.getElementById('ms_password').innerHTML='<span>(密码不能为空)<img src="images/wrong.png"></span>';
        return false;
    } else {
        if (document.getElementById('password').value.length < 6) {
            document.getElementById('ms_password').innerHTML='<span>(密码长度至少六位)<img src="images/wrong.png"></span>';
            return false;
        } else if (document.getElementById('password').value.length > 20) {
            document.getElementById('ms_password').innerHTML='<span>(密码长度不能多与20位)<img src="images/wrong.png"></span>';
            return false;
        } else {
            //有效密码正则式
            var pwd_reg = /^([a-zA-Z0-9_.]){6,20}$/;
            if (!pwd_reg.exec(document.getElementById('password').value)) {
                document.getElementById('ms_password').innerHTML='<span>(密码不符合规定)<img src="images/wrong.png"></span>';
                return false;
            } else {
                document.getElementById('ms_password').innerHTML='<span>(成功)<img src="images/success.png"></span>';
                return true;
            }
        }
    }
}

/**
 * validate_submit(){检查用户名和密码是否填写}
 * @access public
 * @returns {boolean}
 */
function validate_submit() {
    if (!(document.getElementById('username').value.trim()
        && document.getElementById('password').value.trim())) {
        alert("请检查是否填写完成各项必填项目!");
        return false;
    } else {
        return true;
    }
}