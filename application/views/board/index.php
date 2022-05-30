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
 #search_area {
	 width : 800px;
	 margin : 0px auto;
	 margin-bottom : 10px;
 }
</style>
</head>
<body>
    	<div id="page"> </br>
			<h1>게시판</h1>
			<div id="header">
				<img src='/image/back.jpg' width=960; height=150;/>
			</div>
			<div id="menu">
						<span><a href="/board/index">게시판목록</a></span>
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

			<div id="search_area">
				<?php echo validation_errors() ?>
				<form name="frm" method="get" enctype="multipart/form-data" onsubmit="return false">
						<select name="type">
							<option value="title" <?= ($type=='title')?'selected':''?>>제목</option>
						 	<option value="writer" <?= ($type=='writer')?'selected':''?>>작성자</option>
						</select>
						<input type="text" name="search_word" value="<?= (!empty($search_word))?$search_word:''?>" size=20 placeholder='검색어 입력'>
						<button onclick="search(this)" onkeyup="enterkey_search()">검색</button>
				</form>
			</div>

			<table id="tbl">
				<tr>
					<th width=50px;>번호</th>
					<th width=450px;>제목</th>
					<th with=50px;>File</th>	 
					<th width=70px;>작성자</th>
					<th width=155px;>작성일</th>
					<?php if( $id == 'admin'){ ?>
						<th>삭제</th>
					<?php } ?>						
				</tr>
				<?php foreach ($board as $board_item): ?>
					<tr class="row">
						<td><?= $board_item['bno']?></td>
						<td class="board_read" bno="<?= $board_item['bno']?>"><?= $board_item['title']?></td>
						<td><?php echo ($board_item['files'] == null) ? 'X'  : 'O' ?></td>
						<td><?= $board_item['writer']?></td>
						<td><?= $board_item['regdate']?></td>
						<?php if( $id == 'admin'){
						echo '<td class="deleteDomain"><button class="btnDelete">삭제</button></td>';
						}
						?> 
					</tr>
				<?php endforeach ?>
			</table>

			<div class="tbl">
					<tr>
						<td class="test"></td>
					</tr>
			</div>

			<div>
				<?php echo $pagination ?>
			</div>
			<!-- <?php
			// var_dump( $prices['apple'] ?? 300 ); # int(300)
			// var_dump( $prices['banana'] ?? 100 ); # int(100)
			// var_dump( $prices['lemon'] ?? 100 ); # int(200)
			?> -->
     </div>
</body>
<script>
	let board_read = document.querySelectorAll('.board_read');
	let btnDelete = document.querySelectorAll('.btnDelete');
	let id= '<?php echo $id?>';
	
	for(i=0; i < board_read.length; i++){
		let bno = board_read[i].getAttribute('bno');
		board_read[i].addEventListener("click", function(){
			// alert(bno);
			location.href="/board/view/" + bno;
	});
	}

	for(i=0; i < btnDelete.length; i++){
		
		btnDelete[i].addEventListener("click", function(){
			if(!confirm('삭제하시겠습니까??')) return;
			let bno = $(this).parent().parent().find('.board_read').attr('bno');
			location.href="/board/admin_delete/" + bno;
	});
	}

	function search(node) {
		frm.action=`/board/search`;
		frm.submit();
	}

	function enterkey_search() {
		if(window.event.keyCode == 13){
			search();
		}
	}
</script>
</html>
