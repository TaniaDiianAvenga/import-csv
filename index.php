<!DOCTYPE html>
<html>
<head>
    <title>How to Store Form data in CSV File using PHP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<br />
<div class="container">
    <h2 align="center">How to Store Form data in CSV File using PHP</h2>
    <br />
    <div class="col-md-6" style="margin:0 auto; float:none;">
        <form method="post" id = "ldap-form">
            <h3 align="center">Contact Form</h3>
            <br />
            <?php echo $error; ?>
            <div class="form-group">
                <label>Enter Name</label>
                <input type="text" name="name" placeholder="Enter Name" class="form-control" value="<?php echo $name; ?>" />
            </div>
            <div class="form-group">
                <label>Enter Email</label>
                <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>" />
            </div>
            <div class="form-group">
                <label>Enter comment</label>
                <textarea name="comment" class="form-control" placeholder="Enter comment"><?php echo $comment; ?></textarea>
            </div>
            <div class="form-group" align="center">
                <input type="submit" name="submit" class="btn btn-info" value="Submit" />
            </div>
        </form>
    </div>
</div>
</body>
</html>

<script>
    $(function(){
       $('input[type="submit"]').on('click', function(e){
           e.preventDefault();
           $data = $('#ldap-form').serialize();
            $.ajax({
                type: "post",
                url:  "/saveToCSV.php",
                data: $data,
                cache: false,
                success: function(data) {
                    console.log('data', data);
                },
                error: function (error, xml) {
                    console.log('error', error);
                }
            });
       });
    });
</script>