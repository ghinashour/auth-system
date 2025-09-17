<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>
<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $onePost = $conn->query("SELECT * FROM posts WHERE id='$id'");
    $onePost->execute();
    $posts = $onePost->fetch(PDO::FETCH_OBJ);
}
$comments = $conn->query("SELECT * FROM comments WHERE post_id='$id'");
$comments->execute();
$comment = $comments->fetchAll(PDO::FETCH_OBJ);
?>
<div class="row">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $posts->title; ?></h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
        </div>
    </div>
</div>

<div class="row">
    <form method="POST" id="comment_data">
        <div class="form-floating">
            <input name="username" type="hidden" class="form-control" id="username"
                value="<?php echo $_SESSION['username']; ?>">
        </div>
        <div class="form-floating">
            <input name="post_id" type="hidden" value="<?php echo $posts->id; ?>" class="form-control" id="post_id">
        </div>

        <div class="form-floating">
            <textarea rows="9" name="comment" placeholder="body" class="form-control" id="comment"></textarea>
            <label for="floatingPassword">Comment</label>
        </div>

        <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-4" type="submit">Create
            commment</button>
        <div id="msg" class="nothing"></div>
        <div id="delete-msg" class="nothing"></div>
    </form>

</div>

<div class="row">
    <?php foreach ($comment as $singleComment): ?>
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title"><?php echo $singleComment->username; ?></h5>
                <p class="card-text"><?php echo $singleComment->comment; ?></p>
                <?php if (isset($_SESSION['username']) and $_SESSION['username'] == $singleComment->username): ?>
                    <button id="delete-btn" value="<?php echo $singleComment->id; ?>" class=" btn btn-danger mt-3">Delete
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>



<?php require "includes/footer.php"; ?>

<script>
    $(document).ready(function () {

        $(document).on('submit', function (e) {
            //used to prevent refreshing of the page
            e.preventDefault();
            // alert('form submitted')
            var formdata = $('#comment_data').serialize() + '&submit=submit';
            $.ajax({
                type: 'post',
                url: "insert-comments.php",
                data: formdata,
                success: function () {
                    //empty the comment box
                    $('#comment').val(null);
                    $('#username').val(null);
                    $('#post_id').val(null);
                    //once successfully the div of the above will change the class from nothing to  these
                    $('#msg').html('Added successfully').toggleClass("alert alert-success bg-success text-white mt-3");
                    //callling the function that repeat the refreshing automatically 
                    fetch();
                }
            });
        });


        $("#delete-btn").on('click', function (e) {
            //used to prevent refreshing of the page
            e.preventDefault();
            // alert('form submitted')
            var id = $(this).val();
            $.ajax({
                type: 'post',
                url: "delete-comment.php",
                data: {
                    delete: 'delete',
                    id: id
                },
                success: function () {
                    $('#delete-msg').html('Deleted successfully').toggleClass("alert alert-success bg-success text-white mt-3");
                    //callling the function that repeat the refreshing automatically 
                    fetch();

                }
            });
        });



        //refreshing automatically so that new comments appear directly
        function fetch() {
            setInterval(() => {
                $('body').load("show.php?id=<?php echo $_GET["id"]; ?>")
            }, 4000);
        }
    });
</script>