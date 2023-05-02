<?php
$user_name=$_POST['user_name'];
$user_pass=$_POST['user_pass'];
if($user_name=='admin@gmail.com' && $user_pass=='123'){
    echo "Đăng nhập thành công!";
}else{
    echo "Tên đăng nhập hoặc mật khẩu không đúng!";
}