// プロフィール画像編集入力のためのjs
$(document).on("change", "#file_photo", function (e) {
    var reader;
    if (e.target.files.length) {
        reader = new FileReader;
        reader.onload = function (e) {
            var userThumbnail;
            userThumbnail = document.getElementById('thumbnail');
            $("#userImgPreview").addClass("is-active");
            userThumbnail.setAttribute('src', e.target.result);
        };
        return reader.readAsDataURL(e.target.files[0]);
    }
});


//　現在時刻入力のためのjs
$('#nowstart').on('click', function (e) {
    e.preventDefault();
    var $form = $(e.currentTarget).parents('form');
    var Hour = new Date().getHours();
    var Min = new Date().getMinutes();
    $form.find('input[name="starttime"]').val(Hour + ':' + Min);
});

$('#nowend').on('click', function (e) {
    e.preventDefault();
    var $form = $(e.currentTarget).parents('form');
    var Hour = new Date().getHours();
    var Min = new Date().getMinutes();
    $form.find('input[name="endtime"]').val(Hour + ':' + Min);
});

$('#resetstart').on('click', function (e) {
    e.preventDefault();
    var $form = $(e.currentTarget).parents('form');
    var Hour = null;
    var Min = null;
    $form.find('input[name="starttime"]').val(Hour + ':' + Min);
    window.location.reload();
});

$('#resetend').on('click', function (e) {
    e.preventDefault();
    var $form = $(e.currentTarget).parents('form');
    var Hour = null;
    var Min = null;
    $form.find('input[name="endtime"]').val(Hour + ':' + Min);
    window.location.reload();
});