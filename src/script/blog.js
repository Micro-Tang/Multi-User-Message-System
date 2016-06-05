/**
 * Created by tangkun on 16/5/23.
 */
window.onload = function() {
    var message = document.getElementsByName('message');
    var friend = document.getElementsByName('friend');
    var flower = document.getElementsByName('flower');
    for (var i = 0; i < message.length; i++) {
        message[i].onclick = function() {
            centerWindow('message.php?id='+this.title,'message',250,400);
        };
    }
    for (var j = 0; j < friend.length; j++) {
        friend[j].onclick = function() {
            centerWindow('friend.php?id='+this.title,'friend',250,400);
        };
    }
    for (var k = 0; k < flower.length; k++) {
        flower[k].onclick = function() {
            centerWindow('flower.php?id='+this.title,'flower',250,400);
        };
    }
};

function centerWindow(url, name, height, width) {
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open(url, name, 'height='+height+',width='+width+',top='+top+',left='+left);
}