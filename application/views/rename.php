<!-- Page Content -->
<div class="container">

    <!-- Page Heading -->
    <h1 class="my-4">Rename
    <small><?php echo $fileName; ?></small>
    </h1>

    <?php
        //back option
        $linkBack=site_url('DataExplorer/index');
        echo '<div class="row">';
        echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
        echo '<a href="' . $linkBack . '">Back</a>';
        echo '</div>';
        echo '</div>';
    ?>

    <!-- rename form -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item"></div>
        <div class="col-lg-6 col-md-8 col-sm-12 portfolio-item fileViewDiv">
            <form action='<?php echo site_url('DataExplorer/RenameFile/' . $fileName); ?>' method='POST'>
                <div class="form-group">
                    <label for="exampleInputEmail1">New name</label>
                    <input type="text" class="form-control" id="newName" name="newName"/>
                </div>
                <button type="submit" class="btn btn-primary">Rename</button>
            </form>
        </div> 
    </div>

</div>