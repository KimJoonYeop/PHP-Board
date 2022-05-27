<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/home.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <title>게시판</title>
    <style>
    input::placeholder {
        font-size: 30px;
    }

    input[type=text] {
        height: 50px;
        font-size: 30px;
        width: 740px;
        line-height: 100px;
        border: none;
        border-bottom: 1px dotted gray;
        outline: none;

    }

    textarea {
        padding: 10px;
        resize: none;
        
    }
    </style>
</head>

<body>
    <div id="page"> <br />
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
        <?php echo validation_errors(); ?>
        </br></br></br>

        <form action="/board/create" name="frm" method="post" onsubmit='return false'>
            <div style="text-align : center;">
                <input name="title" type="text" placeholder="게시글 제목">
            </div>
        
            <div style="text-align : center;">
                <h2 style="margin : 0px auto; margin-top : 40px; margin-bottom : 5px; width : 740px; text-align : left;">게시글 내용</h2>
                <textarea id="description" name="content" cols="100%" rows="10"></textarea>
            </div>
            <div style="text-align : center; margin-top : 10px;">
               <button type="submit" style="width: 740px;" onclick="frm_submit()">게시글 등록</button>
            </div>
        </form>
    </div>
</body>
<script>
    let id= '<?php echo $id?>';
    function frm_submit(){
        if(id == ''){
            alert('로그인 후 게시글 등록이 가능합니다');
            return;
        }
        if( confirm('게시글을 등록하시겠습니까?') ){
            frm.submit();
        }
    }
</script>
</html>