/**
 * Created by Rosid on 4/14/2018.
 */
function readURL(input, lampiran) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            if(lampiran == 'lampiran1'){
                $('#lampiranPreview1').css('background-image', 'url('+e.target.result +')');
                $('#lampiranPreview1').hide();
                $('#lampiranPreview1').fadeIn(650);
            }else if(lampiran=='lampiran2'){
                $('#lampiranPreview2').css('background-image', 'url('+e.target.result +')');
                $('#lampiranPreview2').hide();
                $('#lampiranPreview2').fadeIn(650);
            }else if(lampiran=='lampiran3'){
                $('#lampiranPreview3').css('background-image', 'url('+e.target.result +')');
                $('#lampiranPreview3').hide();
                $('#lampiranPreview3').fadeIn(650);
            }else{
                $('#lampiranPreview4').css('background-image', 'url('+e.target.result +')');
                $('#lampiranPreview4').hide();
                $('#lampiranPreview4').fadeIn(650);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#lampiran1").change(function() {
    readURL(this, 'lampiran1');
});

$("#lampiran2").change(function() {
    readURL(this, 'lampiran2');
});

$("#lampiran3").change(function() {
    readURL(this, 'lampiran3');
});

$("#lampiran4").change(function() {
    readURL(this, 'lampiran4');
});