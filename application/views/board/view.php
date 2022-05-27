<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/home.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.1/handlebars.js"></script>
    <title>게시판</title>
    <style>
        #box{
            margin : 0px auto; 
            border : 1px solid;
            width : 800px; 
            height : 400px;
            padding : 25px;
            overflow : hidden;
        }
        #title{
            font-size: 1.7em;
            margin-block-start: 0.83em;
            margin-block-end: 0.5em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            color : black;
            border : none;
            background-color : white;
            width : 800px;
        }
        #content{
            width: 95%;
            height: 150px;
            /* border: none; */
            resize: none;
            font-size : 1.1em;
            background-color : white;
            border-color : gray;
        }

        .re hr{
            border : dotted 0.5px gray;
        }

        .re, hr, .ReviewInsert {
            width : 800px;
            margin : 0px auto; 
        }

        .re button {
            width : 58px;
            font-size : 6px;
            padding : 2px;
        }

        .ReviewInsert {
            border : 1px gray solid;
            height : 200px;
            padding : 30px 25px;
        }
    </style>
</head>

<body>
    <div id="page"> <br/>
        <?php echo validation_errors(); ?>
        <form method="post" name="frm" onsubmit="return false">
        <h1>게시판</h1>
        <div id="header">
				<img src='/image/back.jpg' width=960; height=150;/>
			</div>
			<div id="menu">
						<span><a href="/board/index">Home</a></span>
						<span><a href="/board/create">게시판등록</a></span>

						<?php if( $id == ''){ ?>
                             <span style="float:right;"><a href="/main/login_validation">로그인</a></span>
                        <?php }else{ ?>
                             <span style="float:right;"><a href="/main/logout"><span>'.$id.'님 환영합니다!</span>로그아웃</a></span>
                        <?php } ?>


                        <!-- <c:if test="${uid != null}">
                                    <span style="float:right;"><a href="/user/logout"><span>${uid}님 환영합니다!</span>로그아웃</a></span>
                        </c:if> -->
		</div>
            <div style="text-align : right;">
                <button type="button" onclick="location.href='/board';" style="margin-right : 53.75px;">목록이동</button>
            </div>
            <br/>
            <div id="box" >
                    <input type="text" id="title" name="title" value="<?= $board_item['title'] ?>" disabled="disabled"> <hr>
                    
                    <p id="regdate" style="text-align : right;">
                         <?= '<span style="font-weight : bold">' . $board_item['writer'] . '</span>'?>
                         <?php if($board_item['regdate'] != $board_item['updatedate']){
                             echo $board_item['updatedate'].' 수정됨';
                         }else{
                             echo $board_item['regdate'];
                         } ?>
                    </p>
                <div style="text-align : center;">
                    <textarea disabled id="content" name="content"><?php echo $board_item['content']?></textarea>
                </div>
                <?php 
                    if( $id == $board_item['writer'] ){
                ?>
                        <div style="text-align : right; margin-top : 5px;">
                            <button id="btnSubmit" style="display:none;" onclick="form_submit(this)">수정완료</button>
                            <button id="btnUpdate" onclick="form_update(this)">수정</button>
                            <button id="btnDelete" onclick="form_delete(this)">삭제</button>
                            <button id="btnCancel" type="reset">취소</button>
                        </div>
                <?php
                    }
                ?>
            </div> <br/>
    
        <div style="width:850px; margin : 0px auto;">
            <h2>리뷰</h2>
        </div>
            <div class="ReviewInsert">
                <div id="ReviewText" style="text-align : center;">
                    <textarea rows=10 style="width : 100%;" placeholder="리뷰를 입력해주세요" name="reply"></textarea> <br/>
                </div>
                <div style="width : 806px; margin : 0px auto; text-align : right;">
                     <button type="submit" onclick='form_submit2(this)' id="btnUpdate2" style="text-align : right; margin-top : 10px;">등록</button>
                </div>
            </div>
        <br/>
       <?php foreach($reply_list as $reply): ?>
                <div class="re">
                    <span style="font-size: 12px; color: #555; font-weight : bold;"><?= $reply['id']?></span>
                    <?php
                        if( $reply['replyDate'] == $reply['updateDate']){
                    ?>
                            <span style="font-size: 12px; color: #555;"><?= $reply['replyDate']?></span>
                    <?php
                        }else{
                    ?>
                            <span style="font-size: 12px; color: #555;"><?= $reply['updateDate']?> 수정됨</span>
                    <?php
                        }
                    ?>
                   
                    <?php
                        if( $id == $reply['id'] || $id == 'admin'){
                    ?>
                            <div style="display : inline; float : right;">
                                <button class="btnUpdateF" style="display : none;" data-index="<?= $reply['rno']?>" onclick='form_submit3(this)'>수정완료</button>
                                <button class="btnUpdateR" data-index="<?= $reply['rno']?>">수정</button> &nbsp; 
                                <button class="btnDelete"  data-index="<?= $reply['rno']?>" onclick="form_delete2(this)">삭제</button>
                            </div>
                    <?php
                        }
                    ?>	
                    <div>
                        <input type="text" name="reply_test" data-no="<?= $reply['rno']?>" class="reviewText" style="margin-top : 15px; border : none; background-color : white;" value="<?=$reply['reply']?>" disabled="disabled"> <br/><br/>
                    </div>
                </div>
            <hr> <br/>
        <?php endforeach ?> 
        </form>
    </div>
</body>
<script>
    
    let btnUpdate = document.querySelector('#btnUpdate'); //게시글 수정버튼
    let btnSubmit = document.querySelector('#btnSubmit'); //게시글 수정완료버튼
    let btnCancel = document.querySelector('#btnCancel'); //게시글 취소버튼
    let btnDelete = document.querySelector('#btnDelete'); //게시글 삭제버튼

    let btnUpdateF = document.querySelectorAll('.btnUpdateF'); //리뷰 수정완료 버튼
    let btnUpdateR = document.querySelectorAll('.btnUpdateR'); //리뷰 수정버튼
    let btnUpdateD = document.querySelectorAll('.btnDelete'); //리뷰 삭제버튼

    let title = document.frm.title;
    let content = document.frm.content;
    
    let reviewText = document.querySelectorAll('.reviewText');
    
    let bno ='<?= $board_item['bno']?>';
    
   
    //게시글 수정버튼 클릭시
    btnUpdate.addEventListener("click",function(e){
        btnUpdate.style.display='none';
        btnSubmit.style.display='';
        title.removeAttribute('disabled');
        content.removeAttribute('disabled');-
        title.focus();
    });

    //게시글 수정완료버튼 클릭시
    function form_submit(node){
        if(confirm('게시글을 수정하시겠습니까?')){
            frm.action = `/board/update/${bno}`;
            frm.submit();
        }
    }

    function form_delete(node){
        if(!confirm('게시글을 삭제하시겠습니까??')) return;
        frm.action=`/board/delete/${bno}`;
        frm.submit();
    }

    

    //리뷰등록 버튼 클릭시
    function form_submit2(){
        let reply = document.frm.reply.value;
        if(reply == ''){
            alert('리뷰 내용을 입력해주세요!');
        }
        if(confirm('리뷰를 등록하시겠습니까?')){
            frm.action = `/board/review_insert/${bno}`;
            frm.submit();
        }
    }

    //리뷰 수정
    function form_submit3(node){
        let rno = node.dataset.index;
        let test = $(this).data('index');
        alert(test);
        if(!confirm('수정하시겠습니까?')) return;

            frm.action= `/board/review_update/${rno}/${bno}`;
            frm.submit();
        }
    
    //리뷰 내용 텍스트박스 사이즈 자동 조절 출력
    function Checksize(){
        for(i=0; i < reviewText.length; i++){
            let resize = reviewText[i].value.length *2;
            reviewText[i].setAttribute('size',resize);
        }
    }
    Checksize();

    //리뷰 수정 & 완료
    for(i=0; i < btnUpdateR.length; i++){
        btnUpdateR[i].addEventListener("click", function(e){
            let re = $(this).parent().parent();
            re.find('.btnUpdateR').css({display : 'none'});
            re.find('.btnUpdateF').css({display : ''});
            re.find('.reviewText').prop('disabled', false);
            re.find('.reviewText').focus();
        });
        // btnUpdateF[i].addEventListener("click", function(e){
        //     let rec = $(this).parent().parent();
        //     let rno= rec.find('.reviewText').data('no'); //dataset.no, jquery : data('no')
            

        //     if(!confirm('수정하시겠습니까??')) return;
                
        //         frm.action= `/board/review_update/${rno}/${bno}`;
        //         frm.submit();
        //     });
    }

    //리뷰 삭제
    function form_delete2(node){
        let rno = node.dataset.index;
        alert(rno);
        if(confirm('삭제하시겠습니까?')){
            frm.action=`/board/review_delete/${rno}/${bno}`;
            frm.submit();
        }
    }
</script>
</html>