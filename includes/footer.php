</div> <!-- Close container -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Star Rating Plugin -->
<script src="rating-plugin/dist/jquery.star-rating-svg.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {

        // Add comment via AJAX
        $(document).on('submit', '#comment_data', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post('insert-comments.php', formData, function () {
                $('#comment_data textarea').val('');
                $('#msg').html("Comment added").removeClass().addClass("alert alert-success mt-2");
                loadComments();
            });
        });

        // Delete comment via AJAX
        $(document).on('click', '.delete-comment', function () {
            var id = $(this).data('id');
            $.post('delete-comment.php', { id: id }, function () {
                $('#msg').html("Comment deleted").removeClass().addClass("alert alert-success mt-2");
                loadComments();
            });
        });

        // Load comments dynamically
        function loadComments() {
            var postId = <?= $post->id ?>;
            $('#comments-container').load('load-comments.php?post_id=' + postId);
        }

        // Initialize star rating
        $(".my-rating").starRating({
            starSize: 25,
            initialRating: <?= isset($userRating->rating) ? $userRating->rating : 0 ?>,
            callback: function (currentRating, $el) {
                $.post('insert-ratings.php', {
                    rating: currentRating,
                    post_id: <?= $post->id ?>,
                    user_id: <?= $_SESSION['user_id'] ?>
                });
            }
        });

        // Live search
        $("#search_data").on('keyup', function () {
            var search = $(this).val().trim();

            if (search !== '') {
                $.post('search.php', { search: search }, function (data) {
                    $("#search-results").html(data).show();
                    $("main, .row").hide(); // hide main content while showing results
                });
            } else {
                $("#search-results").hide().html('');
                $("main, .row").show(); // show main content again
            }
        });

    });
</script>

</body>

</html>