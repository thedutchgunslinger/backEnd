
function showPreview(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    console.log(1);
    reader.onload = function (e) {
      $("#previewImg").attr("src", e.target.result).attr("class", "showImg");
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function showPreviewVideo(x) {
  x.onkeyup = function () {
    var url = "https://www.youtube.com/embed/" + x.value;
    document.getElementById("previewVideo").src = url;
  };
}

function showPreviewArt(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    console.log(1);
    reader.onload = function (e) {
      $("#previewArt").attr("src", e.target.result).attr("class", "showImg");
    };

    reader.readAsDataURL(input.files[0]);
  }
}
