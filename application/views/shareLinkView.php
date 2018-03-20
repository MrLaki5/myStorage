<!-- Page Content -->
<div class="container">

    <!-- Page Heading -->
    <h1 class="my-4">Share link
    <small><?php echo $fileName; ?></small>
    </h1>

    <!-- text content part -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item"></div>
        <div class="col-lg-6 col-md-8 col-sm-12 portfolio-item fileViewDiv">           
            <div class="alert alert-success"><?php echo base_url() . 'index.php/DataExplorer/FileShare/' . $linkText; ?></div>
            <br/>
            <?php
                $linkBack=site_url('DataExplorer/index');
                echo '<a href="' . $linkBack . '">Back</a>';
            ?>
        </div>
    </div> 
     
</div>