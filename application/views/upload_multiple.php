<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/home.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.1/handlebars.js"></script>
    <title>Document</title>
</head>
<body>
    <div id="page">
        <div class="contatiner">
        </br> </br> </br> 
            <h3>Upload Multiple Files in Codeigniter using Ajax JQuery</h3>
        </div>
        </br> </br> 
        <div class="col-md-6" style="">
            <label>Select Multiple Files</label>
        </div>
        <div class="contatiner">
            <input type="file" name="files" id="files" multiple/>
        </div>
        <div style="clear:both"></div>
        <br/>
        <br/>
        <div id="uploaded_images"></div>
    </div>
</body>
</html>
<script>
    $('#files').change(function(){
        let files = $('#files')[0].files;
        let error = '';
        let form_data = new FormData();
        for(let count = 0; count<files.length; count++)
        {
            let name = files[count].name;
            let extension = name.split('.').pop().toLowerCase(); //배열의 마지막 요소제거, 제거된 요소 리턴문자열의 알파벳 전부 소문자로
            alert(extension);
            if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1){
                error += 'Invalid' + count + " Image File";
        }
        else{
            form_data.append("files[]", files[count]);
        }
    }
    if(!confirm('파일을 등록하시겠습니까?')) return;
    if(error == '')
    { 
       $.ajax({
            url : "<?php echo base_url();?>upload_multiple/upload",
            method : "post",
            data : form_data,
            contentType : false,
            cache : false,
            processData : false,
            beforeSend:function(){
                $('#uploaded_images').html("<label class='text-success'>Uploading...</label>");
            },
            success:function(data){
                $('#uploaded_images').html(data);
                $('#files').val('');
            }
       });
    }
    else{
        alert(error);
    }
});
</script>