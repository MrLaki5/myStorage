<!-- Page Content -->
<div class="container" style="height: 100%;">

    <!-- Page Heading -->
    <h1 class="my-4">Data explorer
    <small><?php echo $relativePath; ?></small>
    </h1>

    <?php
        //check if back option is needed (if dir is not cloud root)
        if(!$isRoot){ 
            $linkBack=site_url('DataExplorer/BackDirection');
            echo '<div class="row">';
            echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
            echo '<a href="' . $linkBack . '">Back</a>';
            echo '</div>';
            echo '</div>';
        }
    ?>

    <!-- image content part -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item"></div>
        <div class="col-lg-6 col-md-8 col-sm-12 portfolio-item">
            <div class="thumbnail">              
                <?php 
                    echo'<div style=""/><img style="width:100%" src="data:image/jpeg;base64,'.base64_encode($fileImage).'"/></div>';
                ?>
            </div>
        </div> 
    </div>
      
</div>
    


