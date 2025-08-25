$("#FileInput").on('change', function(e) {
  
  var labelVal = $(".title").text();
  var fileName = e.target.files[0].name;
  var extension = fileName.split('.').pop().toLowerCase();

  if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'webp' || extension == 'gif') {
    $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
    $(".filelabel i, .filelabel .title").css('color', '#208440');
    $(".filelabel").css('border', '2px solid #208440');

    var reader = new FileReader();
    reader.onload = function(e) {
      $(".preview").attr('src', e.target.result);
    };
    reader.readAsDataURL(e.target.files[0]);
  } else if (extension == 'pdf') {
    $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
    $(".filelabel i, .filelabel .title").css('color', 'red');
    $(".filelabel").css('border', '2px solid red');
    $(".preview").attr('src', '');
  } else if (extension == 'doc' || extension == 'docx') {
    $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
    $(".filelabel i, .filelabel .title").css('color', '#2388df');
    $(".filelabel").css('border', '2px solid #2388df');
    $(".preview").attr('src', '');
  } else {
    $(".filelabel i").removeClass().addClass('fa fa-file-o');
    $(".filelabel i, .filelabel .title").css('color', 'black');
    $(".filelabel").css('border', '2px solid black');
    $(".preview").attr('src', '');
  }

  if (fileName) {
    if (fileName.length > 10) {
      $(".filelabel .title").text('');
    } else {
      $(".filelabel .title").text('');
    }
  } else {
    $(".filelabel .title").text('');
  }
});