<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/login.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <title>게시판</title>
</head>
<style>
    .container {
        width : 800px;
        margin : 0px auto;
        height : 300px;
        text-align: center;
    }
</style>

<body>
    <div id="page"> </br>
        <h1>게시판</h1>
        <div id="header">
            <img src='/image/back.jpg' width=960; height=150; />
        </div>
        <div id="menu">
            <span><a href="/board/index">Home</a></span>
            <span><a href="/board/create">게시판등록</a></span>

            <?php if( $id == ''){
                             echo '<span style="float:right;"><a href="/main/login_validation">로그인</a></span>';
                         }else{
                             echo '<span style="float:right;"><a href="/main/logout"><span>'.$id.'님 환영합니다!</span>로그아웃</a></span>';
                         } ?>


            <!-- <c:if test="${uid != null}">
						<span style="float:right;"><a href="/user/logout"><span>${uid}님 환영합니다!</span>로그아웃</a></span>
            </c:if> -->
        </div>
        <div class="container">
            <br /><br /><br />
            <form method="post" action="/main/login_validation" name="frm" style="position : relative; top : -40px;">
                <p id="title">로그인</p>
                <div class="form-group">
                    <input type="text" name="id" class="form-control" placeholder='Id'/>
                    
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder='Password'/>
                    
                </div>
                <div class="form-group">
                    <button type="submit" name="insert" class="btn" style="background-color: gray;">Login</button>
                    <?php
                echo '<label class="text-danger">'.$this->session->flashdata('error').'</label>';
                ?>
                </div>
                <!-- <div>
                    <p class="text-danger"><?php echo form_error('id'); ?></p>
                    <p class="text-danger"><?php echo form_error('password'); ?></p>
                </div> -->
            </form>
        </div>
    </div>
</body>
<script>
    $(frm.id).focus();
</script>
</html>