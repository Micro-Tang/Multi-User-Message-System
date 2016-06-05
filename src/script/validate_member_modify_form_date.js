/**
 * validate_register_form_date.js
 * Created by tangkun on 16/5/14.
 */

/**
 * validate_username() 验证用户名是否有效
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
 * validate_password 验证密码是否有效
 * @access public
 * @returns {boolean}
 */
function validate_password() {
    if(document.getElementById('password').value.trim().length != 0) {
        if (document.getElementById('password').value.trim().length < 6) {
            document.getElementById('ms_password').innerHTML = '<span>(密码长度至少六位)<img src="images/wrong.png"></span>';
            return false;
        } else if (document.getElementById('password').value.trim().length > 20) {
            document.getElementById('ms_password').innerHTML = '<span>(密码长度不能多与20位)<img src="images/wrong.png"></span>';
            return false;
        } else {
            //有效密码正则式
            var pwd_reg = /^([a-zA-Z0-9_.]){6,20}$/;
            if (!pwd_reg.exec(document.getElementById('password').value)) {
                document.getElementById('ms_password').innerHTML = '<span>(密码不符合规定)<img src="images/wrong.png"></span>';
                return false;
            } else {
                document.getElementById('ms_password').innerHTML = '<span>(成功)<img src="images/success.png"></span>';
                return true;
            }
        }
    } else {
        document.getElementById('ms_password').innerHTML='';
        return true;
    }
}

/**
 * validate_password() 验证两次密码是否一致
 * @access public
 * @returns {boolean}
 */
function validate_password2() {
    if (document.getElementById('password').value.trim().length != 0) {
        if (!(document.getElementById('password').value == "")) {
            if (document.getElementById('password').value == document.getElementById('password2').value) {
                document.getElementById('ms_password2').innerHTML = '<span>(成功)<img src="images/success.png"></span>';
                return true;
            } else {
                document.getElementById('ms_password2').innerHTML = '<span>(两次密码不一致,请检查)<img src="images/wrong.png"></span>';
                return false;
            }
        } else {
            document.getElementById('ms_password2').innerHTML = '<span>(请先填写密码)<img src="images/wrong.png"></span>';
            return false;
        }
    } else {
        document.getElementById('ms_password2').innerHTML='';
        return true;
    }
}

/**
 * validate_email() 验证邮箱有效性
 * @access public
 * @returns {boolean}
 */
function validate_email() {
    var email = document.getElementById('email').value.trim();
    if(email.length != 0) {
        var email_reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+$/;
        if (email_reg.exec(email)) {
            document.getElementById('ms_email').innerHTML='<span>(成功)<img src="images/success.png"></span>';
            return true;
        } else {
            document.getElementById('ms_email').innerHTML='<span>(邮箱不符合规定)<img src="images/wrong.png"></span>';
            return false;
        }
    } else {
        document.getElementById('ms_email').innerHTML='<span>(邮箱不能为空)<img src="images/wrong.png"></span>';
        return false;
    }
}

/**
 * validate_qq 验证qq有效性
 * @access public
 * @returns {boolean}
 */
function validate_qq() {
    var qq = document.getElementById('qq').value.trim();
    if(qq.length != 0) {
        var qq_reg = /^(\d){5,11}$/;
        if (qq_reg.exec(qq)) {
            document.getElementById('ms_qq').innerHTML='<span>(成功)<img src="images/success.png"></span>';
            return true;
        } else {
            document.getElementById('ms_qq').innerHTML='<span>(QQ不符合规定)<img src="images/wrong.png"></span>';
            return false;
        }
    } else {
        document.getElementById('ms_qq').innerHTML='';
        return true;
    }
}

/**
 * validate_url() 验证url有效性
 * @access public
 * @returns {boolean}
 */
function validate_url() {
    var url = document.getElementById('url').value.trim();
    if(url.length != 0) {
        var url_reg = /(((^https?:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)$/g;
        if (url_reg.exec(url)) {
            document.getElementById('ms_url').innerHTML='<span>(成功)<img src="images/success.png"></span>';
            return true;
        } else {
            document.getElementById('ms_url').innerHTML='<span>(URL不符合规定)<img src="images/wrong.png"></span>';
            return false;
        }
    } else {
        document.getElementById('ms_url').innerHTML='';
        return true;
    }
}

/**
 * validate_submit() {验证表单数据完整性}
 * @access public
 * @returns {boolean}
 */
function validate_submit() {
    if (!(document.getElementById('username').value.trim()
        && document.getElementById('email').value.trim()
        )) {
        alert("请检查是否填写完成各项必填项目!");
        return false;
    } else {
        if (!validate_username()) {
            alert('用户名不符合规定');
            return false;
        } else {
            if (!validate_password()) {
                alert('密码不符合规定');
                return false;
            } else {
                if (!validate_password2()) {
                    alert('两次密码不一致');
                    return false;
                } else {
                    if (!validate_email()) {
                        alert('邮箱不符合规定');
                        return false;
                    } else {
                        if (!validate_qq()) {
                            alert('QQ不符合规定');
                            return false;
                        } else {
                            if (!validate_url()) {
                                alert('url不符合规定');
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }
}