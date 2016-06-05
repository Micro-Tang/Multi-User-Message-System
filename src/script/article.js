window.onload = function() {

    var message = document.getElementsByName('message');
    var friend = document.getElementsByName('friend');
    var flower = document.getElementsByName('flower');
    var re = document.getElementsByName('re');

    for (var l = 0; l < re.length; l++) {
        re[l].onclick = function () {
            document.getElementsByTagName('form')[0].title.value = this.title;
        };
    }

    for (var i = 0; i < message.length; i++) {
        message[i].onclick = function () {
            centerWindow('message.php?id=' + this.title, 'message', 250, 400);
        };
    }
    for (var j = 0; j < friend.length; j++) {
        friend[j].onclick = function () {
            centerWindow('friend.php?id=' + this.title, 'friend', 250, 400);
        };
    }
    for (var k = 0; k < flower.length; k++) {
        flower[k].onclick = function () {
            centerWindow('flower.php?id=' + this.title, 'flower', 250, 400);
        };
    }

    var ubb = document.getElementById("ubb");
    var fm = document.getElementsByTagName('form')[0];
    var font = document.getElementById('font');
    var color = document.getElementById('color');
    var html = document.getElementsByTagName('html')[0];


    if (fm !== undefined) {
        fm.onsubmit = function () {
            //验证标题长度
            if (fm.title.value.length < 2 || fm.title.value.length > 40) {
                alert("标题长度需在2-40位");
                fm.title.value = "";
                fm.title.focus();
                return false;
            }
            //验证内容长度
            if (fm.content.value.length < 5) {
                alert("内容字数不得小于5位");
                fm.content.value = "";
                fm.content.focus();
                return false;
            }
            return true;
        };

        fm.t.key = function () {
            showcolor(this.value);
        };

        function content(string) {
            fm.content.value += string;
        }
    }

    html.onmouseup = function () {
        if (font !== null) {
            font.style.display = 'none';
            color.style.display = 'none';
        }
    };

    if (ubb !== null) {
        var ubbimg = ubb.getElementsByTagName('img');
        ubbimg[0].onclick = function () {
            font.style.display = 'block';
        };

        ubbimg[2].onclick = function () {
            content("[b][/b]");
        };

        ubbimg[3].onclick = function () {
            content("[i][/i]");
        };

        ubbimg[4].onclick = function () {
            content("[u][/u]");
        };

        ubbimg[5].onclick = function () {
            content("[s][/s]");
        };

        ubbimg[7].onclick = function () {
            color.style.display = 'block';
            fm.t.focus();
        };

        ubbimg[8].onclick = function () {
            var url = prompt("请输入网址: ");
            if (url) {
                if (/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)) {
                    content('[url]' + url + '[/url]');
                } else {
                    alert("网址不合法");
                }
            }
        };

        ubbimg[9].onclick = function () {
            var email = prompt("请输入邮箱: ");
            if (email) {
                if (/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email)) {
                    content('[email]' + email + '[/email]');
                } else {
                    alert("邮箱不合法");
                }
            }
        };

        ubbimg[10].onclick = function () {
            var image = prompt("请输入图片地址: ");
            content('[img]' + image + '[/img]');
        };

        ubbimg[11].onclick = function () {
            var flash = prompt("请输入FLASH网址: ");
            if (flash) {
                if (/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(flash)) {
                    content('[flash]' + flash + '[/flash]');
                } else {
                    alert("FLASH网址不合法");
                }
            }
        };

        ubbimg[18].onclick = function () {
            if (fm.content.rows <= 12) {
                fm.content.rows += 2;
            }
        };

        ubbimg[19].onclick = function () {
            if (fm.content.rows >= 5) {
                fm.content.rows -= 2;
            }
        };
    }

};

function font(size) {
    document.getElementsByTagName('form')[0].content.value += '[size='+size+'][/size]';
}

function showcolor(color) {
    document.getElementsByTagName('form')[0].content.value += '[color='+color+'][/color]';
}
function centerWindow(url, name, height, width) {
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open(url, name, 'height='+height+',width='+width+',top='+top+',left='+left);
}