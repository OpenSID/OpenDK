<!-- The Modal -->
<div id="myModal" class="modal-image">

    <!-- Modal Content (The Image) -->
    <img class="modal-content-image" id="img01" style="max-height: 500px; object-fit: contain;">

    <!-- The Close Button -->
    <span class="close">&times;</span>
</div>

<script>
    $(document).ready(function() {
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("myImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        $(document).on('click', '#myImg', function(e) {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
            e.preventDefault();
        });

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    });
</script>
